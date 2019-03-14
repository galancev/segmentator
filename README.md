# segmentator
Для лёгкого создания сегментаторов

Для создания нового сегментатора нужно отнаследовать класс нового сегментатора от класса Segmentator, опционально поправить название куки, время её жизни и домен, на котором она должна работать. А так же обязательно определить методы getNewSegment и isCorrectSegment.

Пример:

```php
<?php

use Components\Segmentator\Segmentator;

/**
 * Новый яростный сегментатор
 */
class NewPerfectSegmentator extends Segmentator
{
    /**
     * Имя куки
     * @var string
     */
    protected $cookieName = 'PerfectSegment';

    /**
     * Домен куки
     * @var string
     */
    protected $cookieDomain = '.google.com';

    const SEGMENT_SHOW_BLOCK = 1;
    const SEGMENT_HIDE_BLOCK = 2;

    /**
     * Генерирует новый сегмент
     * @return int
     */
    protected function getNewSegment()
    {
        $rand = mt_rand(0, 100);

        if ($rand <= 66) {
            $segment = self::SEGMENT_SHOW_BLOCK;
        } else {
            $segment = self::SEGMENT_HIDE_BLOCK;
        }

        return $segment;
    }

    /**
     * Проверяет сегмент на корректность
     * @param int $segment
     * @return bool
     */
    protected function isCorrectSegment($segment)
    {
        return in_array($segment, [
            self::SEGMENT_SHOW_BLOCK,
            self::SEGMENT_HIDE_BLOCK,
        ]);
    }

    /**
     * Если сегмент с отображением блока
     * @return bool
     */
    public function isSegmentShowBlock()
    {
        return $this->isSegment(self::SEGMENT_SHOW_BLOCK);
    }

    /**
     * Если сегмент со скрытием блока
     * @return bool
     */
    public function isSegmentHideBlock()
    {
        return $this->isSegment(self::SEGMENT_HIDE_BLOCK);
    }
}

```