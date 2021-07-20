<?php


namespace Pf\Core;


class Table
{


    const PATH = _PF_DIR . '/app/Model/';
    const TEMPLATE = <<<'TEMPLATE'
<?php


namespace App\Model;


class %s extends \Pf\Core\Model
{


    private static $instance = NULL;
    
    
    public function __construct()
    {
    
        parent::__construct('%s');
        
    }
    
    
    public static function getInstance()
    {
    
        if(!isset(self::$instance))
        {
            self::$instance = new self();
        }
        
        return self::$instance;
        
    }
    
    
}

TEMPLATE;


    public static function register()
    {

        $table_list = C('table');

        if(empty($table_list))
        {
            return;
        }

        Dir::createIfNotExists(self::PATH);

        foreach($table_list as $name => $conf)
        {
            $class_name = str_replace('_', '', ucwords(strtolower($name), '_'));
            file_put_contents(self::PATH . $class_name . '.php', sprintf(self::TEMPLATE, $class_name, $name));
        }

    }


}
