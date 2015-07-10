<div id="example-line-container" style="height:4px;"></div>

<script type="text/javascript">
var line = new ProgressBar.Line('#example-line-container', {
    color: '#FCB03C',
    duration: 10000,
    easing: "linear",
    strokeWidth: 0.5,
});

line.animate(1.0);  // Number from 0.0 to 1.0
</script>