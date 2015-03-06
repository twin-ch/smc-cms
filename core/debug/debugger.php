<?php  function head(){?>
<style type="text/css">
.irb_dedugger{margin:20px;height:1000px;font-size:14px;padding:0px;}

.irb_dedugger a{color:#0000DD; text-decoration:none;}
.irb_dedugger a:hover{color:#FF7575;}
.irb_dedugger pre{margin:0}
.irb_dedugger .clear{width:100%;clear:both;}

.irb_dedugger .irb_level{color:red;background:#FECBCC;padding:5px}
.irb_dedugger .irb_mess{color:black; background:#F5EDB1;padding:5px;}
.irb_dedugger .irb_args_head{background:#F5EDB1;padding:15px 5px 5px 15px}
.irb_dedugger .irb_args{padding:5px;border:1px solid #78ADFE;background:#E4FFFF}
.irb_dedugger .irb_debug{border-collapse:collapse;}
.irb_dedugger .irb_debug th{border:#8BA0A9 1px solid;background:#D8D8D8}
.irb_dedugger .irb_debug td{border:#8BA0A9 1px solid;}
.irb_dedugger .irb_callstack{background:#E9E9E9;padding:5px;border-top:#8C999D 1px solid;border-left:#8C999D 1px solid;border-right:#8C999D 1px solid}
.irb_dedugger .irb_trace td{background:#F0F0F0;padding-left:5px}
.irb_dedugger .irb_excerpt{background:#E4FFFF}
.irb_dedugger .irb_listing{padding:0;background:#F4FFFF}
.irb_dedugger .irb_num{float:left;width:4%;background:#7273AD;color:#fff;text-align:right;padding-top:0;font-size:15px;}
.irb_dedugger .irb_code{float:left;padding:0px;font-size:15px;overflow-x:auto;width:96%;white-space: nowrap;}
.irb_dedugger .irb_error_line{background:#FF2D2D;color:#FFFF00;width:100%;display:inline-block}
.irb_dedugger .irb_trace_line{background:#FFFF00;color:#FF0000;width:100%;display:inline-block}


.irb_dedugger .irb_tpl{color:#0000A8;}
.irb_dedugger .irb_tpl_error{color:red; font-weight:bold}

.irb_dedugger .irb_explain{border-collapse:collapse;}
.irb_dedugger .irb_explain th{border:#8C999D 1px solid;background:#D8D8D8;text-align:center}
.irb_dedugger .irb_explain td{border:#8C999D 1px solid;background:#F0F0F0;text-align:center}
.irb_dedugger .irb_php_line{background:#FFFFB0;width:100%;display:inline-block}
</style>
<script type="text/javascript" language="javascript">
function ge(id)
{
    return document.getElementById(id);
}
    
function visible(id)
{
    var display = ge(id).style.display;
    ge(id).style.display = (display == 'none') ? 'table-row' : 'none';
    return false;
}

function visibleDiv(id)
{
    var display = ge(id).style.display;
    ge(id).style.display = (display == 'none') ? 'block' : 'none';
    return false;
}

function visible_all(num)
{
    var i = 1;
    while(i < num)
    {
        if(ge('n_' + i))
           ge('n_' + i).style.display = (ge('n_' + i).style.display == 'none') ? 'table-row' : 'none';
      
        i++   
    }
    return false;
}
</script>
<div class="irb_dedugger">
<?php
}

    if(true === IRB_CONFIG_EXCEPTION)
    { 
        set_exception_handler('setExceptionHandler');        
        set_error_handler('setAllException');
    }

    function setAllException($code, $message, $file, $line)
    {
        if(error_reporting() & $code)
            throw new AllException($message, $code, $file, $line);
    }
    
    function setExceptionHandler($e)
    {    
        head();
        $code  = $e -> getCode();
        $mess  = $e->getMessage();        
        $file  = $e->getFile();
        $line  = $e->getLine();
        $trace = $e->getTrace();
        display($code, $mess, $file, $line, $trace);
    } 
    

class AllException extends Exception 
{
    public function __construct($message, $code, $file, $line) 
    {
        $this->file  = $file;
        $this->line  = $line;        
        parent::__construct($message, $code);
    }
}    
///////////////////////////////////////////////////////////////////

/**
* Обработчик вывода
* @param string $code
* @param string $mess
* @param string $file
* @param string $line
* @param string $trace
* @return void
*/

    function display($code, $mess, $file, $line, $trace)
    { 
        switch($code)
        {
            case E_NOTICE:
                $level = 'PHP Notice: ';  
            break;
            
            case E_WARNING:
                $level = 'PHP Warning: ';
            break;

            case E_USER_NOTICE :
                $level = 'CMS Notice: ';  
            break;
            
            case E_USER_WARNING :
                $level = 'CMS Warning: ';
            break;
            
            case E_USER_ERROR :
                $level = 'CMS: ';
            break;
            
            default :
                $level = 'CMS debugging mode: ';
        }
        
?>
    <div class="irb_level" >
        <?php echo $level; ?>
    </div>
<?php 
        if(basename(dirname($file)) === 'db' && basename($file) === 'mysqli.php')
        {
            echo $mess;
            createPhpTrace($code, $trace, $file, $line);        
        }
        elseif(basename(dirname($file)) === 'debug' && basename($file) === 'debugger.php')
        {
            $cnt  = substr_count($mess, "\n") + 2;
            $nums = array_fill(1, $cnt, true);
?>
            <div class="irb_mess"><?php  ?>
                <strong>Variable value</strong>  
                <strong>in:</strong> <?php echo $trace[0]['file']; ?>
                <strong>on line:</strong> <?php echo $trace[0]['line'];?>
            </div> 
            <div class="irb_listing">
                <div class="irb_num">
                    <code>
                        <?php echo  implode("<br>", array_keys($nums)); ?>
                    </code>
                </div>
                <div class="irb_code">
                    <code><pre>
             
<?php echo $mess; ?></pre></code>
                </div>
                <div class="clear"></div>
            </div> 
<?php       createPhpTrace($code, $trace, $file, $line);      
        }
        elseif(basename($file) === 'irb_template.php')
        { 
?>          
            <div class="irb_mess">
                <?php echo $mess;?>
            </div>    
<?php 
                $file = substr($mess, strrpos($mess, ' ') + 1);
                createTplCode($file, $mess);
                createPhpTrace($code, $trace, $file, $line);
        }
        else
        { ?>        
            <div class="irb_mess">
                <?php echo $mess;?>
                <strong>in:</strong> <a href="#" onclick="return visible('n_error')"><?php echo $file; ?></a>
                <strong>on line:</strong> <?php echo $line;?>
            </div> 
            <div id="n_error">
                <?php createPhpCode(array('file' => $file, 'line' => $line), $trace[0]['args'], true); ?>
            </div>            
                <?php  createPhpTrace($code, $trace, $file, $line); ?>  
<?php   } ?> 
</div>  
<?php 
    }   
/**
* Трассировка PHP
* @param string $code
* @param string $trace
* @param string $file
* @param string $line
* @return void
*/    
    function createPhpTrace($code, $trace, $file, $line)
    {    
?>  
    <div class="irb_callstack">
    <a href="#" onclick="return visible_all(<?php echo count($trace) + 1; ?>)">Call Stack</a>
    </div>
    <table class="irb_debug" width="100%" border="0" cellspacing="0" cellpadding="0">       
        <tr>
            <th>№№</th>
            <th>Space</th>
            <th>Location</th>        
            <th>Аction</th>
            <th>Path</th>
        </tr>
<?php 
        $i = 0;
        $spase = $revers = array_reverse($trace);
        array_unshift($spase, '');
     
        foreach($revers as $stack)
        {
            if(empty($stack))
                continue;
         
            if($stack['function'] === 'setAllException' || $stack['function'] === 'trigger_error')
                continue;
                
            if(false !== strpos($stack['file'], 'eval'))
                continue;

?>
        <tr class="irb_trace">
            <td width="30px">
                <?php echo ++$i; ?>
            </td>
            <td>
                <?php echo !empty($spase[$i - 1]['class']) ? $spase[$i - 1]['class'] : 'GLOBALS'; ?>
            </td>
            <td>
            <a href="#" onclick="return visible('n_<?php echo $i; ?>')">
                ..<?php echo ltrim(substr($stack['file'], strrpos($stack['file'], DIRECTORY_SEPARATOR)), '\\');?>:
                <?php echo $stack['line'];?></a>
            </td>
            <td>
                <code>
                <?php if(!empty($stack['class'])){ 
                           echo ltrim(substr($stack['class'], strrpos($stack['class'], DIRECTORY_SEPARATOR)), '\\') . $stack['type']; ?>
                           <?php
                      }                   
                      echo $stack['function'].'()'; ?>
                </code>
            </td>
            <td>
                <?php echo $stack['file'];?>
            </td>
        </tr>
        <tr class="irb_excerpt" style="display:none" id="n_<?php echo $i; ?>">
            <td colspan="5">
                <?php createPhpCode($stack, $spase[$i]['args']); ?>
            </td>
        </tr>    
<?php  }  ?>     
    </table>
<br /><br /><br /><br /><br />
<br /><br /><br /><br /><br />
<br /><br /><br /><br /><br />
<br /><br /><br /><br /><br />
<?php        
    }
    
/**
* Подготовка листинга php кода
* @param string $stack
* @param bool $error
* @return string
*/    
    function createPhpCode($stack, $args, $error = false)
    { 
        static $num_arg;
        $file = file($stack['file']);
        $line = array();
        $code = '';
        $i    = 0;
        
        $position = $stack['line'];
        $position = ($position <= 10) ? 0 : $position - 10;
        
        foreach($file as $string)
        {
            ++$i;
            
            if($error && $i == $stack['line'])
                $lines[] = '<span class="irb_error_line">'. $i .'</span>';
            elseif($i == $stack['line'])
                $lines[] = '<span class="irb_trace_line">'. $i .'</span>';
            else
                $lines[] =  $i;
           
            $code .= $string;
        }    
?>
        <div class="irb_args_head">
            <a href="#" onclick=" return visibleDiv('arg_<?php echo ++$num_arg; ?>')"><?php 
            echo $error ? 'The current variable table' : 'Arguments'; ?></a>
        </div>
        <div class="irb_args"  style="display:none" id="arg_<?php echo $num_arg; ?>">
            <code><pre><?php 
                ob_start();
                var_dump($args);
                $args = ob_get_clean();
                echo highlightDump($args); 
            ?></pre></code>
        </div>   
        <div class="irb_listing">
            <div class="irb_num">
                <code><?php 
                    $lines = array_slice($lines, $position, 20);
                    echo  implode("<br>", $lines); 
                ?></code>
            </div>
            <div class="irb_code">
                <code><?php echo  highlightString($code, $position); ?></code> 
            </div>
            <div class="clear"></div>
        </div>
<?php
    }

/**
* Подсветка var_dump
* @param string $code
* @return string
*/    
    function highlightDump($code)
    {
?>
<style type="text/css">
.irb_dedugger .type{font-weight:bold;font-style:italic;color:#009500}
</style>
<?php
        preg_match_all('~"(.*?)"[\]\n]~is', $code, $out);
        //$code = preg_replace();
//var_dump($out);
        $strings = array('empty'  => '<span class="empty">empty</span>',
                         'array'  => '<span class="type">array</span>',
                         'object' => '<span class="type">object</span>',
                         'string' => '<span class="type">string</span>',
                         'int'    => '<span class="type">string</span>',
                         
        );
    
        $code = str_replace(array_keys($strings), array_values($strings), $code);
        //$code = preg_replace('~()~uis', '', $code);
        return $code;
    }    
    
/**
* Подсветка php кода
* @param string $code
* @return string
*/    
    function highlightString($code, $position)
    {
        $descr = preg_match('~^[\r\n\s\t]*?<\?php~uis', $code) ? '' : '<?php ';
        $code  = highlight_string($descr . $code, true);
        $lines = preg_split('~<br[\s/]*?>~ui', $code);       
        $lines = array_slice($lines, $position, 20);
        return implode('<br />', $lines);
    }
    
/**
* Трассировка 
* @param string $stack
* @param bool $error
* @return void
*/    
    function createTplCode($file, $mess = '')
    {   
        $nums = array();
     
        if($code = @file_get_contents($file))
        {
            $cnt  = substr_count($code, "\n") + 1;
            $nums = array_fill(1, $cnt, true);
            $code = preg_replace('~<!--//(.*?)-->~usi', 'ᐁ$1ᐃ', $code);
            $code = htmlSpecialChars($code);
            $code = str_replace(' ', '&nbsp;', $code);
            $code = nl2br(preg_replace('~ᐁ(.*?)ᐃ~usi', '<span class="irb_tpl">&lt;!--//$1--&gt;</span>', $code));
            
            preg_match('~\[(.+?)\]~', $mess, $block);
            $code = preg_replace('~&lt;!--//(.*?'. preg_quote($block[1]) .'.*?)&gt;~ui',
                                 '&lt;!--//<span class="irb_tpl_error">$1</span>&gt;', 
                                 $code);
        }
        else
            $code = file_exists($file) ? '<h2>&nbsp;&nbsp;&nbsp;File is empty</h2>' : '<h2>&nbsp;&nbsp;&nbsp;File not found</h2>';
      
?>
    <div class="irb_listing">
        <div class="irb_num">
            <code>
                <?php echo  implode("<br>", array_keys($nums)); ?>
            </code>
        </div>
        <div class="irb_code" style="color:#9D9D9D">
            <code>
                <?php echo  $code; ?>
            </code>
        </div>
        <div class="clear"></div>
    </div>  
<?php 
    } 
    
/**
* Вывести значение переменной 
* @param mixed $stack
* @param bool $error
* @return void
*/ 
    function dbg($variable = '', $test = true) 
    {
        $trace =  debug_backtrace();
        
        if(is_array($variable))
        {
            $value = htmlSpecialChars(print_r($variable, 1));
        }
        elseif(!empty($variable))
        {
            ob_start();
            var_dump($variable);
            $value = ob_get_clean();
        }
        else
            $value = 'Empty';
     
        throw new \Exception($value, 27);
    }  































