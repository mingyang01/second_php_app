<?php
class TestCommand extends Command {

    public function main($args)
    {
        if (isset($args['command'])) {
            if (method_exists($this, $args['command']))
            {
                call_user_func(array($this, $args['command']));
            } else {
                $this->help();
                throw new Exception("Error Processing Request", 1);
            }
        } else {
            $this->help();
        }
    }
    
    public function help()
    {
        echo "please use php yiic cache --command=xxx","\n";
    }

    public function test()
    {
        $params = ['cate'=>1, 'sbase'=>0, 'frame'=>0];
        var_dump($this->util->getPoster($params));
    }

}