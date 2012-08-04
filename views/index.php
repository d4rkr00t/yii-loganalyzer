<?php
Yii::app()->clientScript->registerScript('tabs', <<<JS
(function($){
    $('.loganalyzer').on('click','.stack-btn',function(e){
        $(this).nextAll('.stack-pre').slideToggle('fast');
        e.preventDefault();
        return false;
    });
    
    $('#stack-showall').click(function(e){
        $('.stack-pre').slideDown('fast');
        e.preventDefault();
        return false;
    });
    
    $('#stack-collapseall').click(function(e){
        $('.stack-pre').slideUp('fast');
        e.preventDefault();
        return false;
    });
    
    $('#clear').click(function(e){
        if(!confirm('Deseja apagar o conteudo do Log ?')) {
            e.preventDefault();
            return false;
        }
    });
    
    $('.filter-log').click(function (e) {
        var rel   = $(this).attr('rel'),
            error = $('.log-list .error-line'),
            warn  = $('.log-list .warning-line'),
            info  = $('.log-list .info-line');

        if (rel == 'error') {
            error.slideDown('fast');
            warn.slideUp('fast');
            info.slideUp('fast');
        } else if (rel == 'warning') {
            error.slideUp('fast');
            warn.slideDown('fast');
            info.slideUp('fast');
        } else if (rel == 'info') {
            error.slideUp('fast');
            warn.slideUp('fast');
            info.slideDown('fast');
        }else if (rel == 'all') {
            error.slideDown('fast');
            warn.slideDown('fast');
            info.slideDown('fast');
        }
        
        e.preventDefault();
        return false;
    });
})(jQuery);
JS
);
?>

<div class="loganalyzer">
    <div class="page-header">
        <h1><?php echo $this->title; ?></h1>
    </div>
    
    <div class="row-fluid">
        <a href="<?php echo $this->getUrl(); ?>" id="clear"><span class="label">Clear Log</span></a>
        Log Filter:
        <a href="#" class="filter-log" rel='all'><span class="label label-inverse">All</span></a>
        <a href="#" class="filter-log" rel='error'><span class="label label-important">[error]</span></a>
        <a href="#" class="filter-log" rel='warning'><span class="label label-warning">[warning]</span></a>
        <a href="#" class="filter-log" rel='info'><span class="label label-info">[info]</span></a>
        Stack Trace:
        <a href="#" id="stack-showall"><span class="label">Show All</span></a>
        <a href="#" id="stack-collapseall"><span class="label">Collapse All</span></a>
        <hr>
    </div>

    <div class="row-fluid log-list" style="word-wrap: break-word;">
        <?php
        $flag = false;
        foreach ($log as $l):
            if ($this->filterLog($l)):
                $status = $this->showStatus($l);
                ?>
                <div class="line <?= ($flag = !$flag) ? 'odd' : '' ?> <?php echo $status['status'] ?>-line">
                    <span class="label label-info"><?php echo $this->showDate($l); ?></span>                    
                    <span class="label <?php echo $status['class'] ?>">[<?php echo $status['status']; ?>]</span>
                    <a href="#" class="stack-btn"><span class="label label-inverse">Show Stack trace</span></a>
                    
                    <pre><?php echo $this->showError($l); ?></pre>
                    <pre class="stack-pre" style="display:none;"><?php echo $this->showStack($l); ?></pre>
                </div>
            <?php
            endif;
        endforeach;
        ?>
    </div>
</div>