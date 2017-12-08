<?php
if(!isset($model)){
    $model = array();
    
} ?> 
<?php 
if($this->Paginator->numbers(array('model' => $model))!=''){ ?>
<nav aria-label="Page navigation">
    <ul class="pagination pull-right">
<?php   echo $this->Paginator->prev('<<', array('tag' => 'li'), null, array('tag'=> 'li', 'disabledTag' => 'span','model' => $model));
        echo $this->Paginator->numbers(array('separator'=>'','currentTag' => 'a', 'tag'=> 'li', 'currentClass' => 'active','model' => $model ));
        echo $this->Paginator->next('>>', array('tag'=> 'li'), null, array('tag'=> 'li', 'disabledTag' => 'span','model' => $model)); 
?>
    </ul>
</nav>	
<?php } ?>