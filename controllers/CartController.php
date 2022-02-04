<?php


namespace app\controllers;


use app\models\Cart;
use app\models\Order;
use app\models\OrderProduct;
use app\models\Product;
use yii\helpers\Html;

class CartController extends AppController
{
    public function actionChangeCart()
    {
        $id = \Yii::$app->request->get('id');
        $qty = \Yii::$app->request->get('qty');
        $product = Product::findOne($id);   //если записи нет вернуть фолс
        if(empty($product)){
            return false;
        }
        $session = \Yii::$app->session;     //открытие сессии
        $session->open();
        $cart = new Cart();     //создание экземпляра класса модели
        $cart->addToCart($product, $qty);     //вызов функции с передачей текущего значения $product
        return $this->renderPartial('cart-modal', compact('session'));
    }
    public function actionAdd($id)      //довавление  корзину//
    {
        $product = Product::findOne($id);   //если записи нет вернуть фолс
        if(empty($product)){
            return false;
        }
        $session = \Yii::$app->session;     //открытие сессии
        $session->open();
        $cart = new Cart();     //создание экземпляра класса модели
        $cart->addToCart($product);     //вызов функции с передачей текущего значения $product
        if(\Yii::$app->request->isAjax){        //если запрос аякс, рендер только вида без шаблона
            return $this->renderPartial('cart-modal', compact('session'));
        }
        return $this->redirect(\Yii::$app->request->referrer);      // на случай если отключен яваскрипт в браузере вернуть туда откуда пришел

    }
    public function actionShow()    //отображение корзины//
    {
        $session = \Yii::$app->session;     //открытие сессии
        $session->open();
        return $this->renderPartial('cart-modal', compact('session'));
    }
    public function actionDelItem()     //удаление позиций из козины//
    {
        $id = \Yii::$app->request->get('id');
        $session = \Yii::$app->session;
        $session->open();
        $cart = new Cart();
        $cart->recalc($id);
        if(\Yii::$app->request->isAjax){
        return $this->renderPartial('cart-modal', compact('session'));
        }else{
            return $this->redirect(\Yii::$app->request->referrer);
        }
    }
    public function actionClearCart()    //полная очистка корзины//
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->remove('cart');
        $session->remove('cart.qty');
        $session->remove('cart.sum');
        return $this->renderPartial('cart-modal', compact('session'));
    }
    public function actionCheckout()   //оформление заказа//
    {
        $this->setMeta("Оформление заказа ::" . \Yii::$app->name);
        $session = \Yii::$app->session;
        $order = new Order();
        $order_product = new OrderProduct;
        if($order->load(\Yii::$app->request->post())){
            $order->qty = $session['cart.qty'];
            $order->total = $session['cart.sum'];
            $transaction = \Yii::$app->getDb()->beginTransaction();
            if(!$order->save() || !$order_product->saveOrderProducts($session['cart'], $order->id)){
                \Yii::$app->session->setFlash('error', 'ошибка оформления заказа');
                $transaction->rollBack();
            }else{
                \Yii::$app->session->setFlash('success', 'ваш заказ принят');
                try {
                    \Yii::$app->mailer->compose('order', ['session' => $session])
                        ->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']])
                        ->setTo([$order->email, \Yii::$app->params['adminEmail']])
                        ->setSubject('Заказ с сайта')
                        ->send();

                }catch (\Swift_TransportException $e){
                //    var_dump($e); die;

                }


                $transaction->commit();
                $session->remove('cart');
                $session->remove('cart.qty');
                $session->remove('cart.sum');
                return $this->refresh();
            }


        }

        return $this->render('checkout', compact('session', 'order'));
    }

}