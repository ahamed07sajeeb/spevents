<?php
use SpeventsHelper as H;
defined('_JEXEC') or die;

$user = JFactory::getUser();

$listOrder = $this->escape($this->state->get('list.ordering'));

$listDirn = $this->escape($this->state->get('list.direction'));

$canOrder = $user->authorise('core.edit.state','com_spevents.category');
$saveOrder = $listOrder == 'a.ordering';

if($saveOrder) {
	$saveOrderingUrl = 'index.php?option=com_spevents&task=coupons.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'eventList','adminForm', strtolower($listDirn),$saveOrderingUrl);
}

$sortFields = $this->getSortFields();


JHtml::_('jquery.framework', false);

?>

<script type="text/javascript">
    Joomla.orderTable = function() {
        //table = document.getELementById('sortTable');
        table = document.getElementById('sortTable');
        direction = document.getElementById('directionTable');

        order = table.options[table.selectedIndex].value;

        if (order != '<?php echo $listOrder; ?>') {
            dirn = 'asc';
        } else {
            dirn = direction.options[direction.selectedIndex].value;
        }

        Joomla.tableOrdering(order, dirn, '');
    }

</script>




<form action="<?php echo JRoute::_('index.php?option=com_spevents&view=coupons'); ?>" method="POST" name="adminForm" id="adminForm">

	<?php if (!empty($this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>



	<div id="j-main-container" class="span10" >
		<?php else: ?>
			<div id="j-main-container"></div>
		<?php endif; ?>
		<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
		<div class="clearfix"></div>

		<table class="table table-striped" id="eventList">
			<thead>
			<tr>

				<th class="nowrap center hidden-phone" width="1%">
					<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
				</th>

				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>

				<th>
					<?php echo JHtml::_('grid.sort','JTITLE','a.title',$listDirn,$listOrder); ?>
				</th>
                <th>
                    <?php echo JHtml::_('grid.sort','Code', 'a.code', $listDirn, $listOrder); ?>
                </th>
                <th>
					<?php echo JText::_('Discount Amount'); ?>
				</th>
                <th>
					<?php echo JText::_('Discount Type'); ?>
				</th>
				<th width="1%" class="nowrap center">
					<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.enabled', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>


			</tr>
			</thead>

			<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>

			<tbody>
			<?php foreach($this->items as $i => $item): ?>

				<?php
				$canCheckin = $user->authorise('core.manage', 'com_checkin') ||
					$item->checked_out == $user->get('id') ||
					$item->checked_out == 0;

				$canChange = $user->authorise('core.edit.state', 'com_spevents') && $canCheckin;

				$canEdit = $user->authorise(
					'core.edit',
					'com_spevents.category.' . $item->catid
				);

				?>

				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="1">

					<td class="order nowrap center hidden-phone">
						<?php if($canChange) :
							$disableClassName = '';
							$disabledLabel = '';
							if(!$saveOrder) :
								$disabledLabel = JText::_('JORDERINGDISABLED');
								$disableClassName = 'inactive tip-top';
							endif;
							?>

							<span
								class="sortable-handler hasTooltip <?php echo $disableClassName; ?>"
								title="<?php echo $disabledLabel; ?>">
                            <i class="icon-menu"></i>
                        </span>
							<input type="text" style="display: none;" name="order[]" size="5"
							       class="width-20 text-area-order "
							       value="<?php echo $item->ordering; ?>" >
						<?php else: ?>
							<span class="sortable-handler inactive">
                            <i class="icon-menu"></i>
                        </span>
						<?php endif; ?>

					</td>


					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>

					<td>
						<?php if ($item->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'albums.', $canCheckin); ?>
						<?php endif; ?>

						<?php if ($canEdit) : ?>
							<a class="sp-events-title" href="<?php echo JRoute::_('index.php?option=com_spevents&task=coupon.edit&id='.$item->id);?>">
								<?php echo $this->escape($item->title); ?>
							</a>
						<?php else : ?>
							<?php echo $this->escape($item->title); ?>
						<?php endif; ?>
					</td>
                    <td>
                        <?php echo $item->code; ?>
                    </td>
                    <td>
                        <?php echo $item->amount; ?>
                    </td>
                    <td>
                        <?php echo $item->type; ?>
                    </td>
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->enabled, $i, 'categories.', true,'cb');?>
					</td>
					<td class="hidden-phone center">
						<?php echo $item->id; ?>
					</td>

				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<input type="hidden" name="task" value="" />

		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lilstDirn; ?>" />

		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>


