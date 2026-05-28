<?php

/** @var yii\web\View $this */
/** @var app\models\Transaksi $model */

$this->title = 'Edit Transaksi';
$this->params['breadcrumbs'][] = ['label' => 'Transaksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>