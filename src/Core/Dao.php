<?php


namespace Pf\Core;


class Dao
{


    public static function build()
    {

        $app = \Pf\App::getInstance();
        $app->path_dir_dao = $app->path_dir_base . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Dao' . DIRECTORY_SEPARATOR;
        $app->extend_dao = '.php';
        $app->template_dao = <<<'TEMPLATE'
<?php


namespace App\Dao;


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
        $table_list = C('table');

        if(empty($table_list))
        {
            return;
        }

        Dir::createIfNotExists($app->path_dir_dao);

        foreach($table_list as $name => $conf)
        {

            $class_name = str_replace('_', '', ucwords(strtolower($name), '_'));
            file_put_contents($app->path_dir_dao . $class_name . $app->extend_dao, sprintf($app->template_dao, $class_name, $name));

        }

    }


}
