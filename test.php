<?php
/**
 * 红黑树
 */
class RBTree
{
    public $root;
    public $nil; //哨兵
    
    public function __construct()
    {
        // construct the sentinel
        $this->nil = array("left" => null ,"right" => null,"parent" => null,"color" => "BLACK","isnil" => true,"data" => "sentinel");// data 无意义
        
        // set sentinel as the root
        $this->root = &$this->nil;
        
    }
    
    public function Isnil(&$n)
    {
        return $n["isnil"];
    }
    
    // 按照算法导论
    /* 
    
    PHP 里面没有引用这回事,只有别名,引用本身是一种类型,但是别名则不是一种类型
    别名赋值给另外一个变量并不使其成为一个别名,而引用赋值另一个变量,那个变量也是引用
    PHP中变量名都是别名
    $a = 1;
    $b = &a;
    $a 和 $b 地位一样
    
    别名就如同指针一样,但又有区别
    
    REB BLACK TREE初始化的时候sentinel会作为root
    sentinel相当于NULL节点,树中所有在bst中为NULL的指针均指向sentinel ,包括root的parent指针
    */
    public function insert($n)
    {
        $y = &$this->nil;
        $x = &$this->root;//root是一个引用,$x仍然是引用并且引用正确的对象吗?, $y = $x = $this->root $y仍然引用那个对象?
        //看起来实际情况是 $x 得到的是root所引用对象的拷贝,最终$y也拷贝了一份
        
        while( !$this->Isnil($x) )
        {
            $y = &$x;//每次进入新的子树,y代表root,第一次循环的时候,y会代表root,同时如果循环一次也未运行,y可以检测到树为空
            if ($n["data"] < $x["data"])
                $x = &$x["left"];
            else
                $x = &$x["right"];
        }
        
        
        
        if( $this->Isnil($y))
            $this->root = &$n;
        else if( $n["data"] < $y["data"] )
            $y["left"] = &$n;
        else
            $y["right"] = &$n;
            
        $n["parent"] = &$y;    
        $n["left"] = &$this->nil;//新加入的节点的right left都指向sentinel
        $n["right"] = &$this->nil;
        $n["color"] = "RED";
        $n["isnil"] = false;
        
        $this->insertFixup($n);
        
    }
    
    public function insertFixup(&$node)         
    {    
        
        $n = &$node;
        while ( $n["parent"]["color"]  == "RED" )
        {
            //echo "calling inserFixup,do actually fixup:".$n["data"]."parent:".$n["parent"]["data"]."(".$n["parent"]["color"].")\n";
            // php 中如何表示两个别名指向同一块内存
            // 实际上比较两个别名,PHP是比较它们所指向的值,
            // 如果有两块内存,存放了相同的东西,实际上它们的引用应该是不同的
            // 但是PHP里面会认为相同
            
            // 如果两个引用指向了不同的位置,但是其内容相等,应该有机制可以区别这两个引用
            // 但是PHP是没有的
            
            // PHP中似乎不能直接比较两个引用,一旦比较必然是比较变量本身,
            
            $tmp = &$n["parent"]["parent"];
            if( $n["parent"]["data"] == $tmp["left"]["data"] )
            {
                $y = &$n["parent"]["parent"]["right"];// uncle
                //if uncle is red
                if( $y["color"] == "RED" )
                {
                    $n["parent"]["color"] = "BLACK";
                    $y["color"] = "BLACK";// SET UNCLE to black
                    $n["parent"]["parent"]["color"] = "RED";
                    $n = &$n["parent"]["parent"];
                }
                else  //case 2
                {
                    if ( $n["data"] == $n["parent"]["right"]["data"] )
                    {
                        $n  = &$n["parent"];//将n指向其parent ,然后left rotate
                        $this->leftRotate($n);
                    }
                    $n["parent"]["color"] = "BLACK";
                    $n["parent"]["parent"]["color"] = "RED";
                    $this->rightRotate($n["parent"]["parent"]);                    
                }
            }
            else // 对称的, n的parent是一个right child
            {
                $y = &$n["parent"]["parent"]["left"];// uncle
                //if uncle is red
                if( $y["color"] == "RED" )
                {
                    $n["parent"]["color"] = "BLACK";
                    $y["color"] = "BLACK";// SET UNCLE to black
                    $n["parent"]["parent"]["color"] = "RED";
                    $n = &$n["parent"]["parent"];
                }
                else  //case 2
                {
                    if ( $n["data"] == $n["parent"]["left"]["data"] )
                    {
                        // 如果n是一个 left child
                        $n  = &$n["parent"];
                        $this->rightRotate($n);
                    }
                    $n["parent"]["color"] = "BLACK";
                    $n["parent"]["parent"]["color"] = "RED";
                    $this->leftRotate($n["parent"]["parent"]);                    
                }            
            }
        }
        
        $this->root["color"] = "BLACK";
    }
    
    /*
                         n
                        / \
                       a   y
                          / \
                         b   c

                          y
                         / \
                        n   c
                       / \
                      a   b
    */
    public function leftRotate(&$n)
    {
        $y = &$n["right"];
        $n["right"] = &$y["left"];
        
        if ( !$this->Isnil($y["left"]) )
        {
            $y["left"]["parent"] = &$n;
        }
        
        $y["parent"] = &$n["parent"];
        
        if ( $this->Isnil($n["parent"]) )
        {
            $this->root = &$y;
        }
        else if ( $n["data"] == $n["parent"]["left"]["data"] )//Fatal error: Nesting level too deep - recursive dependency?
        {
            $n["parent"]["left"] = &$y;
        }
        else
        {
            $n["parent"]["right"] = &$y;
        }
        
        $y["left"] = &$n;
        $n["parent"] = &$y;
        
    }

    /*
                         n
                        / \
                       y   a
                      / \
                     b   c

                          y
                         / \
                        b   n
                           / \
                          c   a
    */
    public function rightRotate(&$n)
    {
        $y = &$n["left"];
        $n["left"] = &$y["right"];
        
        if ( !$this->Isnil($y["right"]) )
        {
            $y["right"]["parent"] = &$n;
        }
        
        $y["parent"] = &$n["parent"];
        
        if ( $this->Isnil($n["parent"]) )
        {
            $this->root = &$y;
        }
        else if ( $n["data"] == $n["parent"]["left"]["data"] )
        {
            $n["parent"]["left"] = &$y;
        }
        else
        {
            $n["parent"]["right"] = &$y;
        }
        
        $y["right"] = &$n;
        $n["parent"] = &$y;
        
    }
    
    // 按照数据结构和算法分析里面的操作
    public function delete($data,&$r)
    {
        if ( $r == null )
            return;//没有找到节点,或者视图从一个空树中删除节点
        if ( $data < $r["data"] )
            $this->delete( $data, $r["left"] );
        else if ( $data > $r["data"] )
            $this->delete( $data, $r["right"] );
        else if ( $r["left"] != null && $r["right"] != null )
        {
            //往下的都是$data == $r["data"] ,找到节点,而且其左右均不为空
            
            $min =  $this->findMin( $r["right"] );// y replace z , 
            $r["data"] = $min["data"];
            $this->delete( $r["data"] , $r["right"]);//delete y which in z's right subtree
        }
        else
        {
            //找到,但是该节点最多只有一个child
            $r = ( $r["left"] != null ) ? $r["left"] : $r["right"];
        }
    }
    
    // 检测是否违反红黑树性质, 用于测试插入和删除
    public function checkerror()
    {
        if($this->root["color"] == "RED")
        {
            echo "root must be black \n";
            return;
        }
        
        
    }
    
    public function transplant(&$u,&$v)
    {
        if ( $this->Isnil($u["parent"]) )
            $this->root = &$v;
        else if ( $u["data"] == $u["parent"]["left"]["data"] ) // whats wrong with the php
            $u["parent"]["left"] = &$v;
        else
            $u["parent"]["right"] = &$v;
            
        $v["parent"] = &$u["parent"];
    }
    
    public function delete2($data,&$r)
    {
        if ( $this->Isnil($r)  )
            return ;//没有找到节点
        if ( $data < $r["data"])
            $this->delete2( $data, $r["left"] );
        else if ( $data > $r["data"] )
            $this->delete2( $data, $r["right"] );
        else
        {
            // we find the node , now we can call the algorithm in introduction to algorithm
            $y = &$r;
            $y_origin_color = $r["color"];
            
            if ( $this->Isnil($r["left"]) )
            {
                // simulator the transplant z , z.right
                // 我们没有改变指针间的关系,而是直接改变了变量的内容,将z所在的变量用z.right覆盖
                // 在C++的实现中r是指针的引用,指向某个Node,这个引用的对象是parent的right或left
                // 在那里是修改指针的内容为z.right,
                // 但是PHP里面引用就代表变量本身,我们parent.left只是一个别名,别名实际上等于变量名
                // 我们实际上没有得到parent的right 或 left,而是得到了一个和他等价的,也就是指向同一个变量的变量名
                // 所以我们无法改变引用本身,只能改变其所指向的变量
                $x = &$r["right"];
                $this->transplant($r,$r["right"]);
                //相当于transplant
                
            }
            else if( $this->Isnil($r["right"])  )
            {
                $x = &$r["left"];
                $this->transplant($r,$r["left"]);
            }
            else
            {
                // 有两个 child
                
                $y =  &$this->findMin( $r["right"] ); // 加& 得到节点的引用
                $y_origin_color = $y["color"];
                echo "y.data is ".$y["data"]." ".$r["data"]."\n";
                
                // y has no left child
                $x = &$y["right"];
                
                if ( $y["parent"]["data"] == $r["data"]) 
                {
                    // y 是r的直接child
                    $x["parent"] = &$y;// x could be sentinel , x will 取代y的位置
                } else
                {
                    // y 是right subtree中的某个节点
                    // 要用 y的right 取代y的位置
                    $this->transplant($y,$y["right"]);//因为PHP不是按照指针来区别节点的,因此如果y有两个sentinel节点,transplant函数会失效
                    
                    $y["right"]  = &$r["right"];
                    $y["right"]["parent"] = &$y; // 这里的right不是y原来的parent,而是来自r的 right,对transplant的继续
                    
                }
                
                $this->transplant($r,$y);
                $y["left"] = &$r["left"];//继续y取代r的步骤
                $y["left"]["parent"] = &$y;// left could be sentinel
                $y["color"] = $r["color"];
                

                
            }
        }
        
        if ( $y_origin_color == "BLACK" )
            $this->deleteFixup($x);
    }
    
    /*
    这里要讨论一下,是否会出现,x的parent的两个孩子都是nil的情况
    不可能,因为x的doubly black, 如果x是sentinel,那么x.parent的另一个节点绝不可能是sentinel too
     
                                     p
                                    / \
                                   x   sentinel
    这样的话,从到x的black节点比p到sentinel要多,
    因此
    if ( $x == $x["parent"]["left"] )
    总会得到正确的结果
    */
    public function deleteFixup(&$x)
    {
        while ( $x["data"] != $this->root["data"] && $x["color"] == "BLACK" )        // nest level too deep
        {
            // X is a doubly black
            if ( $x["data"] == $x["parent"]["left"]["data"] ) // nest level too deep
            {
                // 如果x是sentinel,而x是right child,而parent也有一个sentinel的left child,
                // 那么这个判断会失效
                // 发现如果x是sentinel,那么无法判断x是left 还是right
                
                $s = &$x["parent"]["right"]; // sibling
                if (  $s["color"] == "RED" )
                {
                    $s["color"] = "BLACK";
                    $x["parent"]["color"] = "RED";
                    $this->leftRotate($x["parent"]);
                    $s = $x["parent"]["right"];// sibling , now  the sibling is BLACK , not introduce any violation , transform to case 2
                }
                
                if ( $s["left"]["color"] == "BLACK" && $s["right"]["color"] == "BLACK" )
                {
                    $s["color"] = "RED";
                    $x = &$x["parent"];// float up the x , go back to the while iteration , the loop invariant : x is doubly or red blck node hold
                }
                else 
                {
                    if( $s["right"]["color"] == "BLACK" )
                    {
                        // SIBLING IS BLACK , 并且sibling的两个child不同时是BLACK , 如果right是BLACK ,left 一定是RED
                        // 操作是transform到 case 4
                        
                        $s["left"]["color"] = "BLACK";
                        $s["color"] = "RED";// exchange s and s.left , then rightRotate
                        $this->rightRotate($s);
                        $s = &$x["parent"]["right"];
                        // now ,sibling is black ,and sibling has a RED right child , is case 4
                    }
                    
                    // into case 4
                    
                    $s["color"] = $x["parent"]["color"];
                    $x["parent"]["color"] = "BLACK";// SIBLING D PARENT 和 sibling 交换眼色
                    // 等价于
                    //$s["parent"]["color"] = "BLACK";,因为事先知道s的color,因此交换无须中间变量
                    $s["right"]["color"] = "BLACK";// 因为下面要rotate,经过right的路径会减少一个BLACK,因此将right改成黑色
                    $this->leftRotate($x["parent"]);
                    $x = &$this->root;// 完成
                }
            }
            else
            {
                // 如果x是sentinel,而x是right child,而parent也有一个sentinel的left child,
                // 那么这个判断会失效
                // 发现如果x是sentinel,那么无法判断x是left 还是right
                
                $s = &$x["parent"]["left"]; // sibling
                if (  $s["color"] == "RED" )
                {
                    $s["color"] = "BLACK";
                    $x["parent"]["color"] = "RED";
                    $this->rightRotate($x["parent"]);
                    $s = $x["parent"]["left"];// sibling , now  the sibling is BLACK , not introduce any violation , transform to case 2
                }
                
                if ( $s["right"]["color"] == "BLACK" && $s["left"]["color"] == "BLACK" )
                {
                    $s["color"] = "RED";
                    $x = &$x["parent"];// float up the x , go back to the while iteration , the loop invariant : x is doubly or red blck node hold
                }
                else 
                {
                    if( $s["left"]["color"] == "BLACK" )
                    {
                        // SIBLING IS BLACK , 并且sibling的两个child不同时是BLACK , 如果right是BLACK ,left 一定是RED
                        // 操作是transform到 case 4
                        
                        $s["right"]["color"] = "BLACK";
                        $s["color"] = "RED";// exchange s and s.left , then rightRotate
                        $this->leftRotate($s);
                        $s = &$x["parent"]["left"];
                        // now ,sibling is black ,and sibling has a RED right child , is case 4
                    }
                    
                    // into case 4
                    
                    $s["color"] = $x["parent"]["color"];
                    $x["parent"]["color"] = "BLACK";// SIBLING D PARENT 和 sibling 交换眼色
                    // 等价于
                    //$s["parent"]["color"] = "BLACK";,因为事先知道s的color,因此交换无须中间变量
                    $s["left"]["color"] = "BLACK";// 因为下面要rotate,经过right的路径会减少一个BLACK,因此将right改成黑色
                    $this->rightRotate($x["parent"]);
                    $x = &$this->root;// 完成
                }
            }
        }
        
        $x["color"] = "BLACK";
    }
    
    public function & findMin( &$r )
    {
        if ( $this->Isnil($r) )
            return null;
        if ( $this->Isnil($r["left"]) )
            return $r;
        return $this->findMin($r["left"]);//此处不加&,返回的也是别名而不是拷贝
    }
    
    //按层,从左到右输出
    public function printTree()
    {
        // 存储一个数组,是上一层的全部树根
        $roots = array();
        
        //初始只包含树根
        $roots[] = $this->root;
        
        $this->printTreeRecursion($roots);
    }
    
    public function printTreeRecursion($roots)
    {
        $nextroots = array();//当前层的所有节点的left right 组成的数组
        
        if( count($roots) == 0 )//退出条件,某层为空
            return;
        
        for( $i = 0 ; $i < count($roots); $i++ )
        {
            if( $roots[$i] != null)
            {
                echo $roots[$i]["data"]." ";
                $nextroots[] = $roots[$i]["left"];
                $nextroots[] = $roots[$i]["right"];
            }            
        }
        echo "\n";//end of current layer
        
        
        $this->printTreeRecursion($nextroots);
    }
    
    public function printTreePreorder(&$r,$d)
    {
        for( $i = 0 ; $i < $d * 2 ; $i++ )
            echo " ";
        
        if( $this->Isnil($r))    
            echo "nill\n";
        else
            echo $r["data"]."(".$r["color"].") PARENT:".$r["parent"]["data"]."\n";
        
        if( $this->Isnil($r))
            return;
        $this->printTreePreorder($r["left"],$d+1);
        $this->printTreePreorder($r["right"],$d+1);
    }
    
    // 中序可按顺序输出,中间的某个元素是跟
    // 这个元素的左边所有元素是其左树,右边全部是其右树
    public function printTreeInorder(&$r,$d)
    {
        if ( $r != null )
            $this->printTreeInorder($r["left"],$d+1);

        for( $i = 0 ; $i < $d * 2 ; $i++ )
            echo " ";
        
        if( $r == null)    
            echo "nill\n";
        else
            echo $r["data"]."\n";
        
        if( $r != null)            
            $this->printTreeInorder($r["right"],$d+1);
    }

}

$rbt = new RBTree();
echo "hah\n";
//$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 1));
echo "hah\n";
//$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 2));
echo "hah\n";
//$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 3));
echo "hah\n";
//$rbt->printTreePreorder($rbt->root,0);
$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 4));//执行此处的时候出了问题
echo "hah\n";
$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 5));

$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 6));

$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 7));

$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 8));
$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 9));
//$rbt->printTreePreorder($rbt->root,0);

$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 23));

//$rbt->printTreePreorder($rbt->root,0);
/*
下面插入12之后,红黑树被破坏了
之前的树

                          4B
                        /   \
                       2B    6B
                      /  \  /  \
                     1B  3B 5B  8R
                               /  \
                              7B   9B      
                                    \
                                    23R


正确的做法应该是12 会加到 23的left child, RED RED 冲突
uncle是BLACK,z本身是left child,我们应该做一个right rotate

                          4B
                        /   \
                       2B    6B
                      /  \  /  \
                     1B  3B 5B  7B
                                  \
                                  9B      
                                 /  \
                                8R  23R   
                                   /
                                  12R

正确的结果应该是

                          4B
                        /   \
                       2B    6B
                      /  \  /  \
                     1B  3B 5B  8R
                               /  \
                               7B  12B      
                                  /  \
                                 9R  23R   
                                  
*/
$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 12));
//$rbt->printTreePreorder($rbt->root,0);
$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 10));
$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 24));
$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 28));
$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => -12));
//$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => -5));
//$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => -20));
//$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => -3));
$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 102));
$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 90));
$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 72));
$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 720));
$rbt->insert(array("left" => null,"right" => null,"parent" => null,"color" => "RED","isnil" => false,"data" => 121));
$rbt->printTreePreorder($rbt->root,0);

$rbt->delete2(4,$rbt->root);

$rbt->delete2(5,$rbt->root);

$rbt->delete2(8,$rbt->root);
$rbt->delete2(24,$rbt->root);
$rbt->delete2(28,$rbt->root);
$rbt->delete2(9,$rbt->root);
$rbt->delete2(6,$rbt->root);
$rbt->delete2(12,$rbt->root);
echo "haha\n";
$rbt->printTreePreorder($rbt->root,0);

//finishing\

//  红黑树在实际使用的时候,似乎会倾向于像右边倾斜
?>