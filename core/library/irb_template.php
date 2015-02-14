<?php 

namespace library;
/**    
 * IRBTemplate - PHP templating engine   
 * NOTE: Requires PHP version 5 or later     
 * Info: http://irbis-school.com      
 * @author IT studio IRBIS-team    
 * @copyright © 2014 IRBIS-team    
 * @version 0.1    
 * @license http://www.opensource.org/licenses/rpl1.5.txt    
 */   
   
class IRB_Template  
{  

/**     
*   PABLIC PROPERTIES  
*/ 

/**     
* Path to the directory templates   
* @access public  
* @var string     
*/   
    private $tpl_dir     = IRB_DIR_TEMPLATE;     

/**     
* The template type   
* @access public  
* @var string     
*/       
    public  $tpl_php    = true; 
 
/**     
* Sets the file extension template   
* @access public  
* @var string     
*/       
    public  $tpl_ext     = 'tpl';  
 
/**      
* The left-delimiter to specify the variable tags  
* @access public   
* @var string      
*/         
    public $left_delim   = '{';   
/**      
* The right-delimiter to specify the variable tags  
* @access public   
* @var string      
*/     
    public $right_delim  = '}';    

/**      
* Includes automatic provisioning template variables  
* @access public   
* @var bolean     
*/       
    public  $inialize    = true; 
    
/**      
* Debug mode  
* @access public   
* @var bolean     
*/       
    public  $debug       = IRB_CONFIG_DEBUG;     

/**     
* The name of the exception class 
* You can enable trigger_error 
* @access public  
* @var string    
*/      
    public  $exception   = 'trigger_error';   
    

/**      
*    PROTECTED PROPERTIES  
*/  

/**     
* Template content 
* @access protected  
* @var string    
*/   
    protected $_tpl      = '';  

/**     
* Data variables 
* @access protected  
* @var array   
*/  
    protected $_data     = array();   

/**     
* Installed blocks 
* @access protected  
* @var array   
*/  
    protected $_blocks   = array();  

/**     
* Parsed blocks 
* @access protected  
* @var array   
*/  
    protected $_parsed   = array();  

/**     
* Call stack 
* @access protected  
* @var array   
*/  
    protected $_stack    = array();    

/**     
* Errors 
* @access protected  
* @var array   
*/  
    protected $_errors   = array();  

/**     
* Sets a message header error 
* @access protected  
* @var bolean   
*/    
    protected $_errhead  = true;   

/**     
* Result of work 
* @access protected  
* @var string   
*/  
    protected $_total    = '';   
    
/**     
* Сlass name
* @access protected  
* @var string   
*/  
    protected $_class    = __CLASS__;  

    
/**    
*    PABLIC METHODS  
*/      
   
/**     
* Constructor.        
* @param string $tpl_name      The name of the template  
* @param string $block_parent  The name of the parent block 
*/        
    public function __construct($tpl_name, $block_parent = '')   
    {  
        $this->_path = $this->tpl_dir . $tpl_name .'.'. $this->tpl_ext;
        $this->start_delim = $this->left_delim . $this->left_delim;
        $this->end_delim   = $this->right_delim . $this->right_delim;
      
        if(!$this->_tpl = @file_get_contents($this->_path)) 
            $this->_debug('File does not exist or empty');  
       
        if(!empty($block_parent))   
        { 
            $parent_out = $this-> tpl_php ? '<?=$'. $block_parent .'; ?>' 
                                          : $this->left_delim . $block_parent . $this->right_delim;
         
            $this->_tpl = preg_replace('~<!--//\s+('. preg_quote($block_parent, '~')  
                                      .')\s*#*.*?\s+\-\->.*?\\1\s+?end\s*#*.*?\s+?\-\->~uis',   
                                       $parent_out,    
                                       $this->_tpl   
                                       ); 
             
            if($this->debug && false === strpos($this->_tpl, $parent_out)) 
                $this->_debug('parent block ['. $block_parent .'] does not exist or incorrect syntax'); 
        }   
      
        $this->_initiate();    
    }   
   
/**     
* Assign a variable.  
* 
* @example Simplest case: 
* @example $tpl->assign('name', 'value'); 
* @example <?php $name ?> in template 
* 
* @example Array assign: 
* @example $tpl->assign(array('name' => 'value', 'name2' => 'value2')); 
* @example <?php $name ?>  <?php $name2 ?>  in template 
* 
* @access public     
* @param string/array $data     
* @param string/array $value   
* @return object   
*/     
    public function assign($data, $value = '')   
    {   
        if(is_array($data))   
        {   
            $this->_data = array_merge($this->_data, $data); 
            $this->_stack[]['assign'] = $data;               
        }   
        else   
        {   
            $this->_data[$data] = $value;    
            $this->_stack[]['assign'] = array($data => $value);   
        }   

        if(!$this->tpl_php)
            $this->_normalise($this->_data);
        
        return $this;   
    }    
   
/**     
* Sets the block in the template.  
*  
* @example $tpl->setBlock('content'); 
* @example render <!--// content -->...<!--// content end --> 
* 
* @access public      
* @param string $block_name   
* @return object   
*/    
    public function setBlock($block_name)   
    {   
        $block_tag  = $this->start_delim . $block_name . $this->end_delim;   
        $this->_stack[]['setBlock'] = $block_name;  
        $block = '';  
     
        if(isset($this->_blocks[$block_tag]))  
        {  
            $block = $this->_execute($this->_blocks[$block_tag]);  
            $block = $this->_parse($block);   
        }  
        else 
            $this->_errors[$block_name] = true; 
       
        @$this->_parsed[$block_tag] .= $block;   
        return $this;       
    }       
   
/**     
* Clears the contents of the block  
*  
* @example $tpl->clearBlock('row'); 
* @example clears the contents of the block "row" 
* 
* @access public      
* @param string $block_name   
* @return object   
*/    
    public function clearBlock($block_name)   
    {   
        $block_tag  = $this->start_delim . $block_name . $this->end_delim;       
        $this->_stack[]['clearBlock'] = $block_name; 
     
        if(isset($this->_blocks[$block_tag])) 
            $this->_parsed[$block_tag] = '';  
        else 
            $this->_errors[$block_name] = true; 
      
        return $this;       
    }    
    
/**     
* Parse the template     
* @access public      
* @return string    
*/      
    public function parseTpl()   
    {  
        return $this->_parseTpl();   
    }     

/**     
* Rendering the template     
* @access public      
* @return void   
*/        
    public function display()   
    {     
        if(empty($this->_total)) 
            $this->_total = $this->parseTpl();   
       
        echo $this->_total;   
    }   
   
/**     
* Extends the template   
* @access public      
* @param string $tpl   
* @param string $block   
* @return object   
*/        
    public function extendsTpl($tpl, $block)   
    {  
        $child = $this->_parseChild();    
        $parent_tpl = new $this->_class($tpl, $block);          
        $parent_tpl->assign($block, $child);  
     
        foreach($this->_stack as $stack)  
        {    
            $method = key($stack); 
            $parent_tpl->$method($stack[$method]);  
        }  
        
        $this->_total  = $parent_tpl->_parseChild();        
        $this->_checkblock($this->_errors, $parent_tpl->_errors); 
        return $this;     
    }   
   
/** 
*   PROTECTED METHODS   
*/  

/**     
* Parses the child template  
* @access protected        
* @return string   
*/ 
    protected function _parseChild()   
    {  
        return $this->_parseTpl(false);   
    }  

/**     
* Parses the template  
* @access protected   
* $param bolean $check 
* @return string   
*/ 
    protected function _parseTpl($check = true)   
    {   
        $this->_tpl   = $this->_parse($this->_tpl);
        $this->_tpl   = $this->_clear($this->_tpl);
        $this->_total = $this->_execute($this->_tpl); 
     
        if($check) 
            $this->_checkblock($this->_errors); 
       
        return $this->_total;   
    }  

/**     
* Collects in the array contents of all nested blocks 
* @access protected 
* $param string $block 
* @return string   
*/  
    protected function _parse($block)   
    { 
        $block = $this->_replace($block);       
        
        if(!empty($this->_parsed))   
        {   
            $tags = array_keys($this->_parsed);   
         
            foreach($this->_parsed as $name => $cont)   
            {   
                foreach($tags as $tag)  
                {  
                    $this->_parsed[$name] = str_replace($tag,    
                                                        $this->_parsed[$tag],    
                                                        $this->_parsed[$name]   
                                                        );  
                }  
            }   
           
            $block = str_replace($tags, $this->_parsed, $block);               
        }   
       
        return $block;   
    }   
 
/**     
* Executes php code in the template with the given parameters 
* @access protected   
* $param string $block 
* @return string   
*/  
    protected function _execute($block = '')   
    {
        if(!$this->tpl_php)
            return $this->_parsing($block); 
     
        $block = $this->_includesPhp($block);         
        $block = str_ireplace('<?xml', '<xml', $block);
      
        extract($this->_data);         
        ob_start();
            eval('?>'. $block);     
        $block = ob_get_clean(); 
        
        $block = stripslashes($block);
        $block = str_ireplace('<xml', '<?xml', $block);
      
        return $block;   
    }     

/**
* Replacing instruction "include" to contents of the include file
* @param string $block
* @access protected   
* @return string  
*/  
    protected function _includesPhp($block)   
    {
        $pattern = '~(<?[ph=][^\?>]*?)include[\s\'"]+(.*?)\..+?[\'"]+(;*)~uis';
        preg_match_all($pattern, $block, $include);
     
        if(!empty($include[2]))
        {
            foreach($include[2] as $file)
            {    
                $md5   = md5($file);
                $cont  = $this->_includes($file, $md5);
                $block = preg_replace($pattern, '$1 echo "'. addslashes($cont) .'"$3', $block);
            }
        } 
     
        return $block;  
    }
    
/**
* Connection files in the template
* @param array $matсh
* @access protected   
* @return string  
*/  
    protected function _includesPsd($match)   
    { 
        $md5  = md5($match[1]);
        $this->_data[$md5] = $this->_includes($match[1], $md5);
        
        return $this->left_delim . $md5 . $this->right_delim;
    }     
 
/**
* Parse of external template
* @param string $file
* @param string $md5
* @access protected   
* @return string  
*/  
    protected function _includes($file, $md5)   
    {   
        $inc = new $this->_class($file);
      
        if($this->debug)
        {
            $search = array($this->start_delim, $this->end_delim);
            $replace = array('');
            
            foreach($inc->_blocks as $tag => $val)
            {
                $bname = str_replace($search, $replace, $tag);  
                unset($this->_errors[$bname]);
            }
        }
        
        foreach($this->_stack as $stack)  
        {    
            $method = key($stack); 
            $inc->$method($stack[$method]);  
        } 
        
        return $inc->_parseChild(); 
    }     
    
/**     
* Replaces the pseudo variables to values 
* @access protected   
* $param string $block 
* @return string   
*/ 
    protected function _parsing($block = '')   
    {    
        $names  = array_keys($this->_data);
        $valyes = array_values($this->_data); 
        
        $block  = preg_replace_callback('~'. preg_quote($this->left_delim, '~') 
                                           .'FILE ([a-z0-9\._]+?)'
                                           . preg_quote($this->right_delim, '~')
                                           .'~uis',
                                           array($this, '_includesPsd'),
                                           $block);
        
        $tags   = preg_replace('~([a-z0-9\._]+)~uis', 
                              $this->left_delim .'$1'. $this->right_delim,
                              $names
                              );
        
        $block = str_replace($tags, $valyes, $block);  
        return  preg_replace('~<\?[^x].*?\?>~uis', '', $block); 
    }     
    
/**
* Normalization of names array
* @param array $data
* @access protected   
* @return void  
*/  
    protected function _normalise($data)   
    {    
        foreach($data as $name => $value)
        {
            if(is_array($value))
            {
                foreach($value as $key => $val)
                    $names[$name .'.'. $key]  = $val;
            }
            else
                $names[$name] = $value;
        } 
      
        $this->_data = $names;
    } 
    
/**
* Initialization of variables and sampling blocks 
* @access protected   
* @return void  
*/  
    protected function _initiate()   
    {    
        if($this->inialize && $this->tpl_php)   
        {   
            preg_match_all('~\$([a-z0-9_]+)~ui', $this->_tpl, $vars);   
         
            if(!empty($vars[1]))   
            {   
                foreach($vars[1] as $var) 
                { 
                    $this->_data[$var] = ''; 
                } 
            }   
        }  
      
        $this->_tpl = preg_replace('~(<!--//\s+[^#]+)#*.*?(\s+-->)~ui',  
                                   '$1$2$3',  
                                   $this->_tpl 
                                   ); 
     
        preg_match_all('~<!--//\s+([^\s]+?)\s+-->~uis', $this->_tpl, $blocks);   
        $this->_prepare($blocks[1]);  
    }   

/**     
* Recursive extract the contents of nested blocks 
* @access protected   
* $param array $blocks 
* @return void  
*/ 
    protected function _prepare($blocks)   
    {   
        if(is_array($blocks))   
        {   
            foreach($blocks as $block_name)   
            {   
                preg_match('~<!--//\s*'. preg_quote($block_name, '~')    
                          .'\s+-->+[\r\n]*(.*?)[\r\n]*<!--//\s*?'. preg_quote($block_name, '~')    
                          .'\s+end\s+-->~uis',   
                           $this->_tpl,    
                           $blocks_array   
                           );   
             
                if(!empty($blocks_array[1]))   
                {   
                    preg_match_all('~<!--//\s+([^\s]+?)\s+-->~uis',   
                                   $blocks_array[1],    
                                   $blocks_recursion   
                                   );    
                    
                    if(!empty($blocks_recursion[1]))   
                    {   
                        foreach($blocks_recursion[1] as $blocks) 
                        { 
                            $this->_prepare($blocks); 
                        } 
                    }   
                   
                    $tag = $this->start_delim . $block_name . $this->end_delim;   
                    $this->_blocks[$tag] = $this->_replace($blocks_array[1]); 
                }   
            }   
        }   
    }        
     
/**     
* Replaces the block on the token 
* @access protected   
* $param string $tpl 
* @return void  
*/  
    protected function _replace($tpl)   
    { 
        return preg_replace('~<!--//\s+([^\s]+?)\s+-->\n*.*?\\1\s+end\s*-->~uis',    
                            $this->start_delim .'$1'. $this->end_delim,    
                            $tpl   
                            );           
    } 
    
/**     
* Replaces the block on the token 
* @access protected   
* $param string $tpl 
* @return void  
*/  
    protected function _clear($tpl)   
    { 
        return preg_replace('~'. preg_quote($this->start_delim, '~') 
                               .'.*?'
                               . preg_quote($this->end_delim, '~')
                               .'~uis', 
                            '',
                            $tpl   
                            );           
    }  
    
/**     
* Checks for the presence of blocks in the template 
* @access protected   
* $param array $error_blocks 
* $param array/bollean $parent_blocks 
* @return bolean/void  
*/      
    protected function _checkblock($error_blocks, $parent_blocks = false)  
    { 
        if(!$this->debug || empty($error_blocks)) 
            return false; 
      
        if(is_array($parent_blocks)) 
            $error = array_intersect_key($error_blocks, $parent_blocks); 
        else 
            $error = $error_blocks; 
     
        if(!empty($error)) 
        { 
            foreach($error as $bname => $v) 
            { 
                $this->_debug('block ['. $bname .'] does not exist or incorrect syntax'); 
            } 
        }     
    } 

/**     
* Sets the way of displaying errors 
* @access protected   
* $param string $mess 
* @return void  
*/      
    protected function _debug($mess)  
    {    
        if($this->debug) 
        { 
            $mess = '<strong>Template error:</strong> <br>'. $mess 
                  . '<strong> in: </strong> '. $this->_path;
            
            if($this->exception === 'trigger_error') 
            { 
                trigger_error($mess, E_USER_WARNING); 
            } 
            elseif(!empty($this->exception)) 
            { 
                throw new $this->exception($mess, E_USER_WARNING); 
            } 
            else 
            { 
                echo $this->_errhead ? '<div style="color:red; padding:5px; background:#FECBCC">' 
                                       . 'Template error:</div>' : ''; 
              
                echo '<div style="color:black; padding:0 0 5px 5px; background:#FECBCC">'  
                     . $mess 
                     .'</div>'; 
              
                $this->_errhead = false; 
            }  
        } 
    }       
} 