<?php

namespace Kazan\Mailer;

require_once __DIR__ .'/../../autoload.php';

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class Mailer
{
    private $imap;
    private $mailbox;
    private $status;

    public function __construct()
    {
        $this->openImap();
        $this->status = imap_status($this->imap, $this->mailbox, SA_ALL);
    }

    public function __destruct()
    {
        $this->closeImap();
    }

    public function getMessage($number = 0)
    {
        return imap_header($this->imap, $number);
    }

    public function getUnseenMessages()
    {
        $status = imap_status($this->imap, $this->mailbox, SA_ALL);

        if ($status->unseen > 0) {
            $emails = imap_search($this->imap, 'UNSEEN');
            if ($emails) {
                $output = array();
                rsort($emails);
                foreach ($emails as $sequence) {
                    $overview = imap_fetch_overview($this->imap, $sequence, 0);
                    foreach ($overview as $email) {
                        $output[] = array(
                            'msgno'  => $email->msgno,
                            'subject' => $email->subject,
                            'from'    => $email->from,
                            'date'    => $email->date
                        );
                    }
                }

                return json_encode($output);
            }
        }
    }

    private function openImap()
    {
        $yaml = new Parser();
        try {
            $data = $yaml->parse(file_get_contents(__DIR__ .'/conf/config.yml'));

            $this->imap = imap_open($data['hostname'], $data['username'], $data['password'])
                or die('Cannot connect to inbox : ' . imap_last_error());
            $this->mailbox = $data['mailbox'];
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }
    }

    private function closeImap()
    {
        imap_close($this->imap);
    }
}
