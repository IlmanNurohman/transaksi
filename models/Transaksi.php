<?php

declare(strict_types=1);

namespace app\models;

use DateTimeInterface;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Transaksi extends ActiveRecord
{
    public $fotoFile;

    public static function tableName(): string
    {
        return '{{%transaksi}}';
    }

    public function rules(): array
    {
        return [
            [['name', 'description', 'foto'], 'trim'],
            [['name', 'amount', 'date'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description', 'foto'], 'string'],
            [['amount'], 'integer', 'min' => 0],
            [['date'], 'string'],
            [['date'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['fotoFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, png, webp, gif', 'maxSize' => 5 * 1024 * 1024],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Nama',
            'description' => 'Deskripsi',
            'amount' => 'Jumlah',
            'date' => 'Tanggal',
            'foto' => 'Foto',
            'fotoFile' => 'Foto',
        ];
    }

    public function beforeValidate(): bool
    {
        if (is_string($this->date) && $this->date !== '') {
            $this->date = $this->normalizeDateForStorage($this->date);
        }

        return parent::beforeValidate();
    }

    public function afterFind(): void
    {
        parent::afterFind();

        $this->date = $this->normalizeDateForInput($this->date);
    }

    private function normalizeDateForStorage(string $value): string
    {
        $formats = [
            'Y-m-d\TH:i',
            'Y-m-d H:i:s',
            'Y-m-d H:i',
            'Y-m-d',
        ];

        foreach ($formats as $format) {
            $dateTime = \DateTime::createFromFormat($format, $value);

            if ($dateTime instanceof DateTimeInterface) {
                return $dateTime->format('Y-m-d H:i:s');
            }
        }

        return $value;
    }

    private function normalizeDateForInput(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $formats = [
            'Y-m-d H:i:s',
            'Y-m-d H:i',
            'Y-m-d',
        ];

        foreach ($formats as $format) {
            $dateTime = \DateTime::createFromFormat($format, $value);

            if ($dateTime instanceof DateTimeInterface) {
                return $dateTime->format('Y-m-d\TH:i');
            }
        }

        return $value;
    }

    public static function getTotalAmount(): int
{
    return (int) static::find()->sum('amount') ?? 0;
}

public static function getTotalCount(): int
{
    return (int) static::find()->count();
}

public static function getMonthlyCount(): int
{
    return (int) static::find()
        ->where(['>=', 'date', date('Y-m-01 00:00:00')])
        ->count();
}

public static function getMonthlyAmount(): int
{
    return (int) static::find()
        ->where(['>=', 'date', date('Y-m-01 00:00:00')])
        ->sum('amount') ?? 0;
}

public static function getAverageAmount(): int
{
    $count = static::getTotalCount();
    return $count > 0 ? (int) (static::getTotalAmount() / $count) : 0;
}
}