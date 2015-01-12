<?php
    $presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
?>

<?php if ($paginator->getLastPage() > 1): ?>
    <ul class="pagination">
            <?php echo $presenter->render(); ?>
        <li>
            <input type="text" value="<?php echo Request::get('page') ?>" id="gotopage" class="gotopage form-control" onchange="if(isNaN(parseInt(this.value))) {this.value = '';} else {this.value=parseInt(this.value);};"  placeholder="">
            <button id="_goto" type="button" class="btn btn-white" onclick="var n=parseInt(document.getElementById('gotopage').value); if(isNaN(n)|| n <=0 || n > <?php echo $paginator->getLastPage();?>) return alert('页码不存在！'); var url=this.parentNode.previousSibling.previousSibling.children[0].attributes['href'].value.replace(/page=\d*/, 'page=' + n);window.location.href=url">GO</button>
        </li>
        <li>
            <button type="button" class="btn btn-default">共<?php echo $paginator->getTotal(); ?>条记录</button>
        </li>
    </ul>
<?php endif; ?>