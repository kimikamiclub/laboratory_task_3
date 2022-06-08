<?php 

class ResultOfPurchase{
    private bool $bought;
    private int $discount;

    public function __construct(bool $bought, int $discount){
        $this->bought = $bought;
        $this->discount = $discount;
    }

    public function isBought(): bool{
        return $this->bought;
    }

    public function getDiscount(): int{
        return $this->discount;
    }

    public function isDiscount(): bool{
        return $this->discount != 0;
    }
}

class Simulation{
    private int $users_num;
    private int $successful_sells;
    private int $subscribers;
    private int $marketing_cost;
    private int $book_1;
    private int $book_2;
    private int $set_of_2_books;
    private int $authors_box;
    private int $total_income;
    private int $discount_cost;
    private int $discount_users;
    private int $discount_15_users_num;
    private int $remarketing_cost;
    private int $remarketing;

    public function __construct(int $users_num){
        $this->users_num = $users_num;
        $this->successful_sells = 0;
        $this->subscribers = 0;
        $this->marketing_cost = 0;
        $this->book_1 = 0;
        $this->book_2 = 0;
        $this->set_of_2_books = 0;
        $this->authors_box = 0;
        $this->total_income = 0;
        $this->discount_cost = 0;
        $this->discount_users = 0;
        $this->discount_15_users_num = 0;
        $this->remarketing_cost = 0;
        $this->remarketing = 0;
    }

    public function simulate(): void {
        for($i = 1; $i < $this->users_num; ++$i){
            echo $i;

            $result = $this->run();
            if($result->isBought()){
                $this->current_sale = 0;
        
                $this->successful_sells += 1;
                $num_buy = rand(0, 100);
                if($num_buy < 5) {
                    $this->buy_count = 3;
                }
                elseif($num_buy < 20){
                    $this->buy_count = 2;
                }
                else{
                    $this->buy_count = 1;
                }
        
                for($pro_count = 0; $pro_count <= $this->buy_count; $pro_count++){
                    $select = rand(0, 100);
        
                    if($select < 10){
                        $this->book_1 += 1;
                        $this->current_sale += 14;
                    }
                    elseif($select < 40){
                        $this->book_2 += 1;
                        $this->current_sale += 14;
                    }
                    elseif($select < 80){
                        $this->set_of_2_books += 1;
                        $this->current_sale += 28;
                    }
                    else{
                        $this->authors_box += 1;
                        $this->current_sale += 25;
                    }
                }
        
                if($result->isDiscount()){
                    $this->discount_users += 1;
                    $this->discount_cost += $this->current_sale - ($this->current_sale / $result->getDiscount());
        
                    if($result->getDiscount() == 15){
                        $this->discount_15_users_num += 1;
                    }
                }
                $this->total_income += $this->current_sale;
            }
        }
        
        while($this->subscribers < $this->users_num){
            $this->marketing_cost += 20;
            $this->subscribers += rand(460, 1200);
        }
        
        $this->remarketing_cost = $this->remarketing * 2.5;

    }
    private function run(int $prob_of_buy = 20): ResultOfPurchase {
        if($this->successful_of_probability($prob_of_buy)){
            return new ResultOfPurchase(false, 0);
        }
    
        if($this->successful_of_probability(90)){
            return $this->immediately_buy();
        }
        return $this->not_buy();
    }

    private function successful_of_probability(int $probability): bool {
        $value = rand(0, 100);
        return ($value > $probability);

    }

    private function immediately_buy(): ResultOfPurchase {
        if($this->successful_of_probability(25)){
            return new ResultOfPurchase(true, 0);
        }
        return new ResultOfPurchase(true, 10);
    }

    private function not_buy(): ResultOfPurchase {
        if($this->successful_of_probability(2)){
            $this->remarketing += 1;
            return $this->run(10); 
        }
        return $this->newsletter();

    } 

    private function newsletter(): ResultOfPurchase{
        if($this->successful_of_probability(4)){
            $this->remarketing += 1;
            return $this->run(10); 
        }
        return new ResultOfPurchase(true, 15);
    }

}

$simulation = new Simulation(100);
$simulation->simulate();
