<?php

class StreamHandler implements Iterator
{
    private $stream;
    private $isClosed = false;

    public function __construct($stream = 'php://memory')
    {
        $this->stream = fopen($stream, 'r+');
        if ($this->stream === false) {
            throw new Exception('Failed to open stream.');
        }
    }

    public function write($data)
    {
        if ($this->isClosed) {
            throw new Exception('Cannot write to a closed stream.');
        }

        fwrite($this->stream, $data);
        return $this;
    }

    public function read($length = 1024)
    {
        if ($this->isClosed) {
            throw new Exception('Cannot read from a closed stream.');
        }

        return fread($this->stream, $length);
    }

    public function close()
    {
        if ($this->isClosed) {
            throw new Exception('Stream is already closed.');
        }

        fclose($this->stream);
        $this->isClosed = true;
        return $this;
    }

    public function getContents()
    {
        if ($this->isClosed) {
            throw new Exception('Cannot get contents from a closed stream.');
        }

        rewind($this->stream);
        return stream_get_contents($this->stream);
    }

    public function __destruct()
    {
        if (!$this->isClosed) {
            $this->close();
        }
    }

    public function getIterator()
    {
        if ($this->isClosed) {
            throw new Exception('Cannot iterate over a closed stream.');
        }

        rewind($this->stream);
        while (!feof($this->stream)) {
            $data = fgets($this->stream);
            if ($data !== false) {
                yield $data;
            }
        }
    }
}
