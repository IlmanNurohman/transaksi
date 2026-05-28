<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Transaksi;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class TransaksiController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
{
    $dataProvider = new ActiveDataProvider([
        'query' => Transaksi::find()->orderBy(['id' => SORT_DESC]),
        'pagination' => ['pageSize' => 10],
    ]);

    return $this->render('index', [
        'dataProvider'   => $dataProvider,
        'totalAmount'    => Transaksi::getTotalAmount(),
        'totalCount'     => Transaksi::getTotalCount(),
        'monthlyCount'   => Transaksi::getMonthlyCount(),
        'monthlyAmount'  => Transaksi::getMonthlyAmount(),
        'averageAmount'  => Transaksi::getAverageAmount(),
    ]);
}

    public function actionView(int $id): Response|string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

  public function actionCreate(): Response|string
{
    $model = new Transaksi();

    if ($model->load(Yii::$app->request->post())) {

        $model->fotoFile = UploadedFile::getInstance($model, 'fotoFile');

        if ($model->validate()) {

            if ($model->fotoFile) {

                $uploadPath = Yii::getAlias('@webroot/uploads/transaksi/');

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0775, true);
                }

                $fileName = uniqid('foto_') . '.' . $model->fotoFile->extension;

                $fullPath = $uploadPath . $fileName;

                if ($model->fotoFile->saveAs($fullPath)) {
                    $model->foto = '/uploads/transaksi/' . $fileName;
                }
            }

            $model->save(false);

            Yii::$app->session->setFlash(
                'success',
                'Transaksi berhasil disimpan.'
            );

            return $this->redirect([
                'view',
                'id' => $model->id
            ]);
        }
    }

    return $this->render('create', [
        'model' => $model,
    ]);
}

    public function actionUpdate(int $id): Response|string
{
    $model = $this->findModel($id);

    $oldFoto = $model->foto;

    if ($model->load(Yii::$app->request->post())) {

        $model->fotoFile = UploadedFile::getInstance($model, 'fotoFile');

        if ($model->validate()) {

            if ($model->fotoFile) {

                $uploadPath = Yii::getAlias('@webroot/uploads/transaksi/');

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0775, true);
                }

                $fileName = uniqid('foto_') . '.' . $model->fotoFile->extension;

                $fullPath = $uploadPath . $fileName;

                if ($model->fotoFile->saveAs($fullPath)) {

                    // hapus foto lama
                    if ($oldFoto) {

                        $oldPath = Yii::getAlias('@webroot') . $oldFoto;

                        if (is_file($oldPath)) {
                            unlink($oldPath);
                        }
                    }

                    $model->foto = '/uploads/transaksi/' . $fileName;
                }

            } else {

                // kalau tidak upload baru
                $model->foto = $oldFoto;
            }

            // save TANPA validate lagi
            $model->save(false);

            Yii::$app->session->setFlash(
                'success',
                'Transaksi berhasil diperbarui.'
            );

            return $this->redirect([
                'view',
                'id' => $model->id
            ]);
        }
    }

    return $this->render('update', [
        'model' => $model,
    ]);
}

    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        // Hapus file foto kalau ada
        if ($model->foto !== null && $model->foto !== '') {
            $fotoPath = Yii::getAlias('@webroot') . $model->foto;
            if (is_file($fotoPath)) {
                unlink($fotoPath);
            }
        }

        $model->delete();

        Yii::$app->session->setFlash('success', 'Transaksi berhasil dihapus.');

        return $this->redirect(['index']);
    }

    protected function findModel(int $id): Transaksi
    {
        if (($model = Transaksi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Transaksi tidak ditemukan.');
    }
}