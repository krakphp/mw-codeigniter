<?php

namespace Krak\Mw\Http\Package\CodeIgniter;

use Zend\Diactoros,
    Psr\Http\Message\ResponseInterface;

class CIEmitter implements Diactoros\Response\EmitterInterface
{
    private $output;

    public function __construct(\CI_Output $output) {
        $this->output = $output;
    }

    public function emit(ResponseInterface $resp) {
        $this->emitStatusLine($resp);
        $this->emitHeaders($resp);
        $this->emitBody($resp);
    }

    private function emitStatusLine(ResponseInterface $response) {
        $reasonPhrase = $response->getReasonPhrase();
        $this->output->set_header(sprintf(
            'HTTP/%s %d%s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            ($reasonPhrase ? ' ' . $reasonPhrase : '')
        ));
    }

    private function emitHeaders(ResponseInterface $response) {
        foreach ($response->getHeaders() as $header => $values) {
            foreach ($values as $value) {
                $this->output->set_header(sprintf(
                    '%s: %s',
                    $header,
                    $value
                ));
            }
        }
    }

    private function emitBody(ResponseInterface $response) {
        $this->output->set_output((string) $response->getBody());
    }
}
