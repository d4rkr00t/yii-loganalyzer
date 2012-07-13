<div class="loganalyzer">

<div class="page-header">
  <h1><?php echo $this->title; ?></h1>
</div>

<ul class="nav nav-pills">
  <li class="active">
    <a href="<?php echo $this->getUrl(); ?>">Очистить лог</a>
  </li>
</ul>

<hr>    

<div class="row-fluid">
    Фильтрация лога: 
    <a href="#" class="filter-log" rel='all'><span class="label label-inverse">Все</span></a>
    <a href="#" class="filter-log" rel='error'><span class="label label-important">[error]</span></a>
    <a href="#" class="filter-log" rel='warning'><span class="label label-warning">[warning]</span></a>
    <a href="#" class="filter-log" rel='info'><span class="label label-info">[info]</span></a>
    <hr>
</div>

<div class="row-fluid log-list" style="word-wrap: break-word;">

<?php $i=1; foreach ($log as $l): ?>

    <?php if ($this->filterLog($l)): ?>

        <?php $status = $this->showStatus($l); ?>

        <div class="line <?=($i%2==0)?'odd':''?> <?php echo $status['status'] ?>-line">
            <span class="label label-info"><?php echo $this->showDate($l); ?></span>
            
            <?php $status = $this->showStatus($l); ?>
            <span class="label <?php echo $status['class'] ?>">[<?php echo $status['status']; ?>]</span>

            <br>

            <pre><?php echo $this->showError($l); ?></pre>
            <span class="label label-inverse">Stack trace</span> <br>
            <pre><?php echo $this->showStack($l); ?></pre>

        </div>

    <?php endif ?>

<?php $i++; endforeach ?>

</div>

<script type="text/javascript">
    
    $(document).ready(function () {
        $('.filter-log').click(function () {
            if ($(this).attr('rel') == 'error') {
                $('.log-list .error-line').show();
                $('.log-list .warning-line').hide();
                $('.log-list .info-line').hide();
            }

            if ($(this).attr('rel') == 'warning') {
                $('.log-list .error-line').hide();
                $('.log-list .warning-line').show();
                $('.log-list .info-line').hide();
            }

            if ($(this).attr('rel') == 'info') {
                $('.log-list .error-line').hide();
                $('.log-list .warning-line').hide();
                $('.log-list .info-line').show();
            }

            if ($(this).attr('rel') == 'all') {
                $('.log-list .error-line').show();
                $('.log-list .warning-line').show();
                $('.log-list .info-line').show();
            }

            return false;
        });
    });

</script>

</div>