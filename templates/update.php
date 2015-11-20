<?php
echo 'this is the update page<br>';
$msg = <<<EOC
<h1>Build your auto Update features Here</h1>
<p>Each CMS will have special requirements for hosting your created plugin/module/extension</p>
<p>Be sure the url the cms is checking for updates has the following structure<br/>
<blockquote style="background-color:#CCC; color:#000; display: inline-block; padding:35px;font-style:italic">
u/{pluginName}/update/{action}/{any needed additional parameters
</blockquote>
EOC;
echo $msg;
?>