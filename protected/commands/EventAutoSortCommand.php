<?php
class EventAutoSortCommand extends Command
{
    public function main($args)
    {
        $this->eventAutoSort->AutoSort('2052');
    }
}
?>