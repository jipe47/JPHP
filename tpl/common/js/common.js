function JphpShowLoading(id, onLeft, onRight)
{
	$("#"+id).html(onLeft+'<img src="tpl/common/images/loading_bar.gif" title="Loading" alt="Loading" />'+onRight);
}