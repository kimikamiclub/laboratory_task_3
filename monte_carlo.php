<?php

function simulation(int $prob_of_buy = 20): array {
    $ad = rand(0, 100);
    if($ad > $prob_of_buy){
        return ["bought"=> false, "discount" => 0];
    }

    $buy_immediately = rand(0, 100);
    if($buy_immediately < 10){
        return immediately_buy();
    }
    return not_buy();
}

function immediately_buy(): array {
    $discount = rand(0, 100);
    if($discount > 25){
        return ["bought"=> true, "discount" => 0];
    }
    return ["bought"=> true, "discount" => 10];
}

function not_buy(): array {
    global $remarketing;
    $not_buy = rand(0, 100);
    if($not_buy > 2){
        $remarketing += 1;
        return simulation(10); 
    }
    return newsletter();
}

function newsletter(): array {
    global $remarketing;
    $not_buy = rand(0, 100);
    if($not_buy > 4){
        $remarketing += 1;
        return simulation(10); 
    }

    return ["bought"=> true, "discount"=> 15];
}



$users_num = 100000;
$successful_sells = 0;
$subscribers = 0;
$marketing_cost = 0;

$book_1 = 0;
$book_2 = 0;
$set_of_2_books = 0;
$authors_box = 0;

$total_income = 0;
$discount_cost = 0;
$discount_users = 0;
$discount_15_users_num = 0;

for($i = 1; $i < $users_num; ++$i){
    $result = simulation();
    if($result["bought"] == true){
        $current_sale = 0;

        $successful_sells += 1;
        $num_buy = rand(0, 100);
        if($num_buy < 5) {
            $buy_count = 3;
        }
        elseif($num_buy < 20){
            $buy_count = 2;
        }
        else{
            $buy_count = 1;
        }

        for($pro_count = 0; $pro_count <= $buy_count; $pro_count++){
            $select = rand(0, 100);

            if($select < 10){
                $book_1 += 1;
                $current_sale += 14;
            }
            elseif($select < 40){
                $book_2 += 1;
                $current_sale += 14;
            }
            elseif($select < 80){
                $set_of_2_books += 1;
                $current_sale += 28;
            }
            else{
                $authors_box += 1;
                $current_sale += 25;
            }
        }

        if($result["discount"] != 0){
            $discount_users += 1;
            $discount_cost += $current_sale - $current_sale / $result["discount"];

            if($result["discount"] == 15){
                $discount_15_users_num += 1;
            }
        }
        $total_income += $current_sale;
    }
}

while($subscribers < $users_num){
    $marketing_cost += 20;
    $subscribers += rand(460, 1200);
}

$remarketing_cost = $remarketing * 2.5;

echo "Probability of buying the book is " . ($successful_sells / $users_num) * 100 . " %\n";
echo "Total cost for instagram ad " . $marketing_cost . "\n";
echo "Total cost for discounts " . $discount_cost . "(" . $discount_cost / $total_income * 100 . " %)\n";
echo "Total users subscribed newsletter " . (100 * $discount_15_users_num) / 4 . "\n";
echo "Number of people who bought " . $successful_sells . "\n";
echo "How much did it cost on average to get one customer " . (($marketing_cost + $remarketing_cost) / $successful_sells) . "\n";
echo "People who used discounts " . $discount_users . "\n";
echo "Total income " . $total_income - $marketing_cost - $remarketing_cost . "\n";
echo "Profit per user " . (($total_income - $marketing_cost - $remarketing_cost) / $users_num) . "\n";
