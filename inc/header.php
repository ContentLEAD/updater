<section class="title-block">
    <h1 class="title align-left justify-left"><?php echo $page_title; ?></h1>
    <h2 class="title align-right justify-right"><?php if(isset($con)){ $con->show_env(); } echo $sub_title; ?></h2>
</section>