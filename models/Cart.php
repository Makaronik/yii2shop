<?php


namespace app\models;


use yii\base\Model;

class Cart extends Model
{
    public function addToCart($product, $qty = 1)
    {
        $qty = ($qty == '-1') ? -1 : 1;     //правка для плюс минус кол-ва в оформлениии заказа
        if(isset($_SESSION['cart'][$product->id])){             // если в корзине есть продукт, т.е. если есть в $_SESSION['cart'] текущий идентификатор $product->id
            $_SESSION['cart'][$product->id]['qty'] += $qty;     // то добавляем к ['qty'] переданое значение $qty, увеличиваем общ кол-во данного товара в корзине
        }else{
            $_SESSION['cart'][$product->id] = [             //если такого элемента нет, то создание элемента.
                'title' => $product->title,
                'price' => $product->price,
                'qty' => $qty,
                'img' => $product->img,
            ];
        }
        $_SESSION['cart.qty'] = isset($_SESSION['cart.qty']) ? $_SESSION['cart.qty'] + $qty : $qty;     //  если кол-во существует прибавляем к нему текущее значение, если нет, то создаем
        $_SESSION['cart.sum'] = isset($_SESSION['cart.sum']) ? $_SESSION['cart.sum'] + $qty * $product->price : $qty * $product->price;     //  если сумма существует прибавляем к ней кол-во * на цену, если нет, умножаем кол-во на цену и создаем сумму
        if($_SESSION['cart'][$product->id]['qty'] == 0){
            unset($_SESSION['cart'][$product->id]);
        }
    }
    public function recalc($id)
    {
        if(!isset($_SESSION['cart'][$id])){
            return false;
        }
        $qtyMinus = $_SESSION['cart'][$id]['qty'];
        $sumMinus = $_SESSION['cart'][$id]['qty'] * $_SESSION['cart'][$id]['price'];
        $_SESSION['cart.qty'] -= $qtyMinus;
        $_SESSION['cart.sum'] -= $sumMinus;
        unset($_SESSION['cart'][$id]);

    }
}