<?php
// 购物车类
namespace Home\Controller;

class CartController extends BaseController {
	private $cart_name; // 购物车名
	private $item_name; // 购物车名
	
	/**
	 * 购物车结构图
	 * $cart_name; // 购物车名
	 * $cart_num; // 商品总数
	 * $cart_num1; // 商品个数
	 * $cart_amount; // 商品总金额
	 *
	 * $cart = array(
	 * {
	 * 'cart_name'=>'cart',
	 * 'cart_num'=>0,
	 * 'cart_num1'=>0,
	 * 'cart_amount'=>0,
	 * 'cart_shipfee'=>0,
	 * 'cart_items'=>array(
	 * array(
	 * 'id'=>1,
	 * 'name'=>'item1',
	 * 'price'=>100,
	 * 'indexpic'=>'',
	 * 'num'=>1,
	 * 'ext'=>null
	 * )
	 * )
	 * })
	 */
	// 初始化购物车
	public function _initialize() {
		$cart_name = 'cart_name';
		$this->cart_name = $cart_name;
		if (! isset ( $_SESSION [$this->cart_name] )) {
			$data = array (
					'cart_name' => $cart_name,
					'cart_num' => 0,
					'cart_num1' => 0,
					'cart_amount' => 0,
					'cart_shipfee' => 0,
					'cart_items' => array () 
			);
			session ( $this->cart_name, $data );
		}
	}
	public function index() {
		$this->show ( 'welcome to my cart.', 'utf-8' );
	}
	public function addAll($item_ids = 0, $num = 1, $ext = null) {
		$arr = str2arr ( $item_ids );
		$arr = array_filter ( $arr );
		$arr = array_unique ( $arr );
		if (count ( $arr ) > 0) {
			foreach ( $arr as $key ) {
				$this->addCart ( $key, $num, $ext );
			}
			$this->success ( 1 );
		} else {
			$this->error ( 0 );
		}
	}
	
	/**
	 * 加入收藏夹
	 *
	 * @param number $item_id        	
	 */
	public function addFav($item_id = 0) {
		$where = array ();
		$where ['userid'] = get_userid ();
		$where ['productid'] = $item_id;
		$db = M ( 'fav' )->where ( $where )->find ();
		if ($db) {
			$this->success ( 1 );
		} else {
			$data = array ();
			$db = M ( 'content_product' )->find ( $item_id );
			$data ['userid'] = get_userid ();
			$data ['addip'] = get_client_ip ();
			$data ['productid'] = $db ['id'];
			$data ['title'] = $db ['title'];
			$data ['indexpic'] = $db ['indexpic'];
			$data ['price'] = $db ['price'];
			M ( 'fav' )->add ( $data );
			$this->success ( 1 );
		}
	}
	
	/**
	 * 添加：ajax返回
	 *
	 * @param number $item_id        	
	 * @param number $num        	
	 * @param string $ext        	
	 */
	public function add($item_id = 0, $num = 1,$attrs='', $ext = null) {
		$arr=array('yanse'=>'hongse','chicun'=>'chicun');
		$result=$this->addCart ( $item_id, $num,$attrs, $ext );
		if($result===-1){
			$this->error('对不起，库存不足！');
		}else{
			if ($result) {
				$this->success ( 1 );
			} else {
				$this->error ( '对不起，加入购物车失败！' );
			}
		}
	}
	public function edit($item_id = 0, $num = 1, $ext = null) {
		$result=$this->editCart ( $item_id, $num, $ext );
		if($result===-1){
			$this->error('对不起，库存不足！');
		}else{
		if ($result) {
			$this->success ( 1 );
		} else {
			$this->error ( '对不起，修改购物车失败！' );
		}
		}
	}
	public function del($item_id = 0, $ext = null) {
		if ($this->delCart ( $item_id, $ext )) {
			$this->success ( 1 );
		} else {
			$this->error ( 0 );
		}
	}
	public function ept($shop_id = 0) {
		if ($this->emptyCart ( $shop_id )) {
			$this->success ( 1 );
		} else {
			$this->error ( 0 );
		}
	}
	public function loadCart() {
		return (session ( $this->cart_name ));
	}
	public function getIdList($shop_id = 0) {
		$cart = (session ( $this->cart_name ));
		$items = ($cart ['cart_items']);
		foreach ( $items as $key => $value ) {
			if ($items [$key] ['shop_id'] == $shop_id) {
				$data [] = $items [$key] ['id'];
			}
		}
		return $data;
	}
	public function getNum($shop_id = 0) {
		if ($shop_id == 0) {
			$cart = session ( $this->cart_name );
			$this->success ( $cart ['cart_num'] . '|' . to_price ( $cart ['cart_amount'] + $cart ['cart_shipfee'] ) );
		} else {
			$cart = ($this->getCartInfo ( $shop_id ));
			$this->success ( $cart ['cart_num'] . '|' . to_price ( $cart ['cart_amount'] + $cart ['cart_shipfee'] ) );
		}
	}
	
	/**
	 * 检查库存
	 * 
	 * @param number $productid        	
	 * @param number $num        	
	 */
	public function checkStock($productid = 0, $num = 0) {
		$stock = get_product_stock ( $productid );
		if ($num > $stock) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * 添加产品，如果存在加数量，否则加产品
	 *
	 * @param number $item_id        	
	 * @param number $num        	
	 * @param string $ext        	
	 */
	public function addCart($item_id = 0, $num = 1,$attrs='', $ext = null) {

		$cart = session ( $this->cart_name );

		$idx = $this->exists ( $item_id, $ext );

		
		$stock = $this->checkStock ( $item_id, intval($cart ['cart_items'] [$idx] ['num']) + $num );
		if (! $stock) {
			return - 1;
		}
		if ($idx) {
			$data = array (
					'id' => $item_id,
					'name' => $cart ['cart_items'] [$idx] ['name'],
					'price' => $cart ['cart_items'] [$idx] ['price'],
					'indexpic' => $cart ['cart_items'] [$idx] ['indexpic'],
					'num' =>  $num,
					'ext' => $cart ['cart_items'] [$idx] ['ext'],
					'sortpath' => $cart ['cart_items'] [$idx] ['sortpath'],
					'attrs'=>$attrs
			);
			$cart ['cart_items'] [$idx] = $data;
			session ( $this->cart_name, $cart );
			$this->updateCartInfo ();
			return true;
		} else {
			if ($item_id == 0) {
				return false;
			} else {
				// 唯一需要读取的数据库数据：$name,$price
				$name = null;
				$price = null;
				$db = M ( 'content_product' )->find ( $item_id );
				if ($db) {
					$name = $db ['title'];
					$price = $db['price'];
					$shop_id = $db ['shop_id'];
					$indexpic = $db ['indexpic'];
					$sortpath = $db ['sortpath'];
					if (! (is_numeric ( $num ) && is_numeric ( $num ))) {
						return false;
					} else {
						$data = array (
								'id' => $item_id,
								'name' => $name,
								'price' => $price,
								'indexpic' => $indexpic,
								'num' => $num,
								'ext' => $ext,
								'sortpath' => $sortpath,
								'attrs'=>$attrs
						);
						$cart ['cart_items'] [$item_id . '_' . $ext] = $data;
						session ( $this->cart_name, $cart );
						$this->updateCartInfo ();
						return true;
					}
				} else {
					return false;
				}
			}
		}
	}
	
	/**
	 * 修改产品
	 *
	 * @param number $item_id        	
	 * @param number $num        	
	 * @param string $ext        	
	 */
	public function editCart($item_id = 0, $num = 0, $ext = null) {
		$stock = $this->checkStock ( $item_id, $num );
		if (! $stock) {
			return - 1;
		}
		$cart = session ( $this->cart_name );
		$idx = $this->exists ( $item_id, $ext );
		if ($idx) {
			if ($num == 0) {
				return ($this->delCart ( $item_id, $ext ));
			} else {
					$data = array (
							'id' => $item_id,
							'name' => $cart ['cart_items'] [$idx] ['name'],
							'price' => $cart ['cart_items'] [$idx] ['price'],
							'indexpic' => $cart ['cart_items'] [$idx] ['indexpic'],
							'num' => $num,
							'ext' => $cart ['cart_items'] [$idx] ['ext'],
							'sortpath' => $cart ['cart_items'] [$idx] ['sortpath'],
					);
					$cart ['cart_items'] [$idx] = $data;
					session ( $this->cart_name, $cart );
					$this->updateCartInfo ();
					return true;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * 删除产品
	 *
	 * @param number $item_id        	
	 * @param string $ext        	
	 */
	public function delCart($item_id = 0, $ext = null) {
		$cart = session ( $this->cart_name );
		$idx = $this->exists ( $item_id, $ext );
		if ($idx) {
			unset ( $cart ['cart_items'] [$idx] );
			session ( $this->cart_name, $cart );
			$this->updateCartInfo ();
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 清空购物车
	 *
	 * @param number $item_id        	
	 * @param string $ext        	
	 */
	public function emptyCart($shop_id = 0) {
		session ( $this->cart_name, null );
		return true;
	}
	
	// 检查商品是否存在，若存在，返回$key
	public function exists($item_id = 0, $ext = null) {
		$cart = session ( $this->cart_name );
		$items = $cart ['cart_items'];
		foreach ( $items as $key => $value ) {
			if ($items [$key] ['id'] == $item_id && $items [$key] ['ext'] == $ext) {
				return $key;
				break;
			}
		}
		return false;
	}
	
	// 更新购物车统计
	public function updateCartInfo() {
		$cart = session ( $this->cart_name );
		$items = $cart ['cart_items'];
		$num = 0;
		$num1 = 0;
		$amount = 0;
		foreach ( $items as $key => $value ) {
			$num1 += 1;
			$num += $items [$key] ['num'];
			$amount += $items [$key] ['price'] * $items [$key] ['num'];
		}
		$cart ['cart_amount'] = to_price ( $amount );
		$cart ['cart_num'] = $num;
		$cart ['cart_num1'] = $num1;
		$cart ['cart_shipfee'] = to_price ( $this->getShipfee ( $amount ) );
		session ( $this->cart_name, $cart );
		return true;
	}
	protected function getShipfee($n = 0) {
		return 0;
		$freight = lbl ( 'freight' );
		if (isN ( $freight )) {
			return 0;
		} else {
			
			$arr = parse_field_attr ( $freight );
			$arr = arr2clr ( $arr );
			ksort ( $arr );
			$i = 0;
			foreach ( $arr as $k => $v ) {
				if ($n >= $i && $n < $k) {
					return $v;
					break;
				}
				$i = $k;
			}
			return 0;
		}
	}
	
	public function info(){
		$cart = session ( $this->cart_name );
		sort($cart['cart_items']);
		$this->ajaxReturn($cart);
	}
	
	// 按门店统计
	public function getCartInfo() {
		$cart = session ( $this->cart_name );
		
		$items = $cart ['cart_items'];
		$num = 0;
		$num1 = 0;
		$amount = 0;
		foreach ( $items as $key => $value ) {
				$num1 += 1;
				$num += $items [$key] ['num'];
				$amount += $items [$key] ['price'] * $items [$key] ['num'];
		}
		$stat ['cart_amount'] = $amount;
		$stat ['cart_num'] = $num;
		$stat ['cart_num1'] = $num1;
		$stat ['cart_shipfee'] = $cart ['cart_shipfee'];
		return $stat;
	}


	
	
}
?>