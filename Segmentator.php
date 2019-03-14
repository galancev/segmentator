<?php

namespace Components\Segmentator;

use ReflectionClass;
use ReflectionException;

/**
 * Сегментатор
 * Class Segmentator
 * @package Components\Segmentator
 */
abstract class Segmentator
{
    /**
     * Имя куки
     * @var string
     */
    protected $cookieName = '';

    /**
     * Время жизни куки
     * @var int
     */
    protected $cookieLifeTime = 60 * 60 * 24 * 30;

    /**
     * Путь к директории куки
     * @var string
     */
    protected $cookiePath = '/';

    /**
     * Домен куки
     * @var null|string
     */
    protected $cookieDomain = null;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->generateAndSaveCookieName();
    }

    /**
     * Возвращает, нужно ли настроить имя куки
     * @return bool
     */
    private function isNeedSetCookieName()
    {
        return ($this->getCookieName() === '');
    }

    /**
     * Генерирует и сохраняет имя куки сегментатора
     */
    private function generateAndSaveCookieName()
    {
        if ($this->isNeedSetCookieName()) {
            try {
                $this->setCookieName((new ReflectionClass($this))->getShortName());
            } catch (ReflectionException $exception) {
                $this->setCookieName('Segment');
            }
        }
    }

    /**
     * Сохранить имя куки
     * @param string $cookieName
     */
    private function setCookieName($cookieName)
    {
        $this->cookieName = $cookieName;
    }

    /**
     * Возвращает имя куки
     * @return string
     */
    private function getCookieName()
    {
        return $this->cookieName;
    }

    /**
     * Возвращает значение куки
     * @return mixed
     */
    private function getCookieValue()
    {
        return $_COOKIE[$this->getCookieName()];
    }

    /**
     * Сохраняет значение куки
     * @param $cookieValue
     */
    private function setCookieValue($cookieValue)
    {
        setcookie($this->getCookieName(), $cookieValue, time() + $this->cookieLifeTime, $this->cookiePath, $this->cookieDomain);
        $_COOKIE[$this->getCookieName()] = $cookieValue;
    }

    /**
     * Генерирует новый сегмент и сохраняет его
     * @return mixed
     */
    private function generateAndSaveSegment()
    {
        $segment = $this->getNewSegment();

        $this->setCookieValue($segment);

        return $segment;
    }

    /**
     * Возвращает текущий сегмент
     * @return mixed
     */
    public function getCurrentSegment()
    {
        $segment = $this->getCookieValue();

        if (!$this->isCorrectSegment($segment))
            $segment = $this->generateAndSaveSegment();

        return $segment;
    }

    /**
     * Возвращает, соответствует ли текущий сегмент переданному
     * @param mixed $segment Проверяемый сегмент
     * @return bool
     */
    public function isSegment($segment)
    {
        return ($this->getCurrentSegment() == $segment);
    }

    /**
     * Возвращает новый сегмент согласно логике в этом методе
     * Для реализации работоспособности сегментатора нужно определить этот метод
     *
     * Пример:
     *
     * return 1;
     *
     * @return mixed
     */
    abstract protected function getNewSegment();

    /**
     * Возвращает корректность сегмента согласно логике в этоме методе
     * Для реализации работоспособности сегментатора нужно определить этот метод
     *
     * Пример:
     *
     * return in_array($segment, [
     *   1,
     * ]);
     *
     * @param mixed $segment
     * @return bool
     */
    abstract protected function isCorrectSegment($segment);
}
