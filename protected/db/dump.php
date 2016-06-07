<?php

function dumpQuery(\yii\db\ActiveQuery $query)
{
    echo '<pre>';
    var_export($query->prepare(\Yii::$app->db->queryBuilder)->createCommand(\Yii::$app->db)->rawSql);
    echo '</pre>';
    
    Yii::$app->end();
}


$this->widget('ImperaviRedactorWidget', array(
    'selector' => '.redactor',
    'options' => array(
        'lang' => 'ru',
    ),
    'plugins' => array(
        'fullscreen' => array(
            'js' => array('fullscreen.js',),
        ),
        'clips' => array(
            // You can set base path to assets
            'basePath' => 'application.components.imperavi.my_plugin',
            // or url, basePath will be ignored.
            // Defaults is url to plugis dir from assets
            'baseUrl' => '/js/my_plugin',
            'css' => array('clips.css',),
            'js' => array('clips.js',),
            // add depends packages
            'depends' => array('imperavi-redactor',),
        ),
    ),
));

$this->widget('ImperaviRedactorWidget', array(
    // You can either use it for model attribute
    'model' => $my_model,
    'attribute' => 'my_field',

    // or just for input field
    'name' => 'my_input_name',

    // Some options, see http://imperavi.com/redactor/docs/
    'options' => array(
        'lang' => 'ru',
        'toolbar' => false,
        'iframe' => true,
        'css' => 'wym.css',
    ),
));

$this->widget('ImperaviRedactorWidget', array(
    // The textarea selector
    'selector' => '.redactor',
    // Some options, see http://imperavi.com/redactor/docs/
    'options' => array(),
));