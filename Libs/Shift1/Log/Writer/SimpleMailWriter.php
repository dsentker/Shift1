<?php
namespace Shift1\Log\Writer;

class SimpleMailWriter extends AbstractWriter {

    /**
     * @var string
     */
    protected $format = '%timestamp% %levelName% (%level%): %message%';

    /**
     * @var string
     */
    protected $receiver;

    /**
     * @var string
     */
    protected $subject = 'New log';

    /**
     * @var string
     */
    protected $sender = 'logs@example.com';

    /**
     * @param string $receiver The receiver's e-Mail Address
     */
    public function __construct($receiver) {
        $this->receiver = $receiver;
    }

    /**
     * @return string
     */
    public function getReceiver() {
        return $this->receiver;
    }

    /**
     * @param string $subject
     * @return void
     */
    public function setSubject($subject) {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * @param string $format
     * @return void
     */
     public function setFormat($format) {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * @param string $sender
     * @return void
     */
    public function setSender($sender) {
        $this->sender = $sender;
    }

    /**
     * @return string
     */
    public function getSender() {
        return $this->sender;
    }

    /**
     * @return void
     */
    public function write() {

        $lines = array();

        foreach($this->getEvents() as $event) {
            /** @var \Shift1\Log\Event\Event $event */
            $translate = array(
                '%timestamp%' => $event->getTimestamp(),
                '%levelName%' => $event->getErrorLevelName(),
                '%level%'     => $event->getErrorLevel(),
                '%message%'   => $event->getMessage(),
            );
            $lines[] = \strtr($this->getFormat(), $translate);
        }

        $message  = "A new log was sent: \r\n\r\n";
        $message .= \implode(\PHP_EOL, $lines);

        $header = "From: {$this->getSender()} \r\n" .
        "Reply-To: {$this->getSender()} \r\n" .
        'X-Mailer: PHP/' . \phpversion();

        @\mail($this->getReceiver(), $this->getSubject(), $message, $header);
    }

}