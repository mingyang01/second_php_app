<?php
class RecommendTodayListCommand extends Command
{
    public function main($args)
    {
        $this->recommend->todayListToTomorrow();
    }
}
?>