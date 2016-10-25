<?php if (!defined('THINK_PATH')) exit();?><div id="plot_<?php echo ($type); ?>_<?php echo ($id); ?>" class="jqplot-target"></div>
	
<!--[if lt IE 9]><script type="text/javascript" src="__THEME__/js/jqplot/excanvas.min.js"></script><![endif]-->
<script type="text/javascript" src="__THEME__/js/jqplot/jquery.jqplot.min.js"></script>

<script type="text/javascript" src="__THEME__/js/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="__THEME__/js/jqplot/plugins/jqplot.highlighter.js"></script>

<link rel="stylesheet" type="text/css" href="__THEME__/js/jqplot/jquery.jqplot.min.css" />


<style type="text/css">
#plot_<?php echo ($type); ?>_<?php echo ($id); ?> .jqplot-point-label {
  border: 1.5px solid #aaaaaa;
  padding: 1px 3px;
  background-color: #eeccdd;
}
.jqplot-target {
	color: #333;
}
</style>

<script type="text/javascript">
(function(id){
	var  args = {};
	args.obj =  new Array();
	args.ticks = new Array();
	args.x = new Array();
	args.y = new Array();
	<?php echo $jsHtml; ?>
	args.id = id

	$.jqplot(args.id,args.obj, {
		title: args.title,                          //图表表头标题
		axes: {
			xaxis: { min: args['x'][0], max: args['x'][1]},       				//准确控制x轴最大值及最小值
			yaxis: { min: args['y'][0], max: args['y'][1], numberTicks: args['y'].numberTicks},     //准确控制y轴最大值及最小值,间隔个数
			xaxis: {
				ticks:args.ticks,
				renderer: $.jqplot.CategoryAxisRenderer
			}
		},            
		highlighter: {
			lineWidthAdjust: 50,  
			sizeAdjust: 10,  
			showTooltip: true, 
			tooltipLocation: 'nw', 
			tooltipAxes: 'xy',  
			tooltipSeparator: ',' 
		}
	});

})('plot_<?php echo ($type); ?>_<?php echo ($id); ?>');
</script>