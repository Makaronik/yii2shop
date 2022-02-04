    <div class="table-responsive">
        <table  style="width: 100%; border: 1px solid #ddd; border-collapse: collapse;">
            <thead>
            <tr>
                <th style="padding: 8px; border: 1px solid #ddd;">Наименование</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Кол-во</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Цена</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Сумма</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($session['cart'] as $id => $items): ?>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd;"><?= $items['title'] ?></td>
                    <td style="padding: 8px; border: 1px solid #ddd;"><?= $items['qty'] ?></td>
                    <td style="padding: 8px; border: 1px solid #ddd;"><?= $items['price'] ?></td>
                    <td style="padding: 8px; border: 1px solid #ddd;"><?= $items['price'] * $items['qty'] ?></td>
                </tr>
            <?php endforeach; ?>

            <tr>
                <td colspan="3" style="padding: 8px; border: 1px solid #ddd;">Итого:</td>
                <td id="cart-qty" style="padding: 8px; border: 1px solid #ddd;"><?= $session['cart.qty'] ?></td>
            </tr>
            <tr>
                <td colspan="3" style="padding: 8px; border: 1px solid #ddd;">На сумму:</td>
                <td id="cart-sum" style="padding: 8px; border: 1px solid #ddd;">$<?= $session['cart.sum'] ?></td>
            </tr>
            </tbody>
        </table>
    </div>


