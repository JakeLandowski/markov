<?php

$text = "Hash Tables are symbol tables that turn a key into a 32-bit integer representation of that key in order to use it as an index in a plain array structure. This gives you constant access times, which is extremely good speed. The downside is that in order to hash an object you have made, you need to override and define a solid hashing function that can uniquely convert your object into a number, it also needs to do so consistently, and you waste a bit of memory initially. One way to do this is to accumulate your object's fields and multiply them by prime numbers, giving them more chances of dividing into unique indices later. Another factor in creating unique hashes is trying to make use of all of an int's 32 bits, and not disregarding the least significant or most significant bits. Once you have your hashing function, you then just modulus by the container size, or better yet, the largest prime of that size, this is how you convert your large hash to a usable index. There are 2 type of hash table storage techniques, separate chaining, where each slot of the table is a linked list, and when multiple objects fall on the same index (a collision) you instead append it to the linked list, and dynamically grow that slot. The second type is linear probing, where you place the element at the next available slot when it falls on an already filled slot. One part that was confusing to me was at the start of the chapter, the author gives examples of hashing functions that involves a variable R, but it never explains what R represents afaik, so I have no idea how to interpret the algorithm it gives. int hash = (((day * R + month) % M) * R + year) % M; Also as far as separate chaining go, I know one benefit is how easy it can grow without having to resize, but wouldn't another good option be just more hash tables in each index instead of linked lists? Since it's standard to have excess storage anyway and you can resize them along with resizing the primary array, this would be really fast if you had a lot of collisions.";
$text2 = "Not every company wants that
Some just want super smart people
and you're super smart
but if you try to be camping jake, you're not going to come off as smart, because you're trying to appease the interviewer Don't worry about what they want
Worry about being true to yourself jake!!!
You will do a lot better if you do that
I'm going to give you some inspirational bullshit
But you are smart and talented, and someone is going to want to hire you for who YOU are, not camping jake
Why wouldn't a software company want you to be passionate about software. You said you like camping lOL

Dude
Why don't you just be real and say you like playing HOTS
and you're most passionate about software. It sounds like they are terrible interviewers.  So the manager is probably just terrible at his job
And the other dude it could have been one of his first interviews

being the interviewer. You learned how to use the light rail
And you went to seattle
and you got shoes for next time
And who knows, maybe they'll call you back
Did you bring your car receipt? when i started grading i was like “wait what the fuck?”
So anyway
18 of 24 students got 70% or higher
average was 76%
I might offer 5 points
if they do something
but
I’m mostly fine with the results
I feel bad for the dude that got 35% though";

class MarkovMap
{
    private $_map = [];
    private $_cum = [];
    private $_order = 4; // 3 is the lowest we can go while still making sense

    public function parseText(&$text)
    {
        $len = strlen($text);
        $gram;
        $charAfter;

        for($i = 0; $i < $len - $this->_order; $i++)
        {
            $gram = substr($text, $i, $this->_order);
            $charAfter = substr($text, $i + $this->_order, 1);

            if(!array_key_exists($gram, $this->_map)) 
                $this->_map[$gram] = [];

            if(!isset($this->_map[$gram][$charAfter])) 
                $this->_map[$gram][$charAfter] = 1;
            else
                $this->_map[$gram][$charAfter]++;

        }

        // echo '<pre>';
        // print_r($this->_map);
        // echo '</pre>';
    }

    public function cumulateProbabilities()
    {
        $acc;

        foreach($this->_map as $gram => $charSet)
        {
            $acc = 0;

            foreach($charSet as $char => $count)
            {
                $acc += $count;
                $this->_cum[$gram]['probs'][$acc] = $char;
                $this->_cum[$gram]['max'] = $acc;
            }
        }

        // echo '<pre>';
        // print_r($this->_cum);
        // echo '</pre>';
    } 

    public function generate()
    {
        $seed = array_rand($this->_map);
        $gram;
        $point;
        
        for($i = 0; $i < 2000; $i++)
        {
            $gram = substr($seed, $i, $i + $this->_order);
            $point = rand(0, $this->_cum[$gram]['max']);
            $seed .= $this->findFirstRange($point, $this->_cum[$gram]['probs']);
        }

        return $seed;
    }

    private function findFirstRange(&$index, &$array)
    {
        foreach($array as $range => $element)
        {
            if($index <= $range) return $element;
        }

        return '';
    }
}

// Script start
$rustart = getrusage();




$markov = new MarkovMap;

$markov->parseText($text2);
$markov->cumulateProbabilities();

echo $markov->generate();

// Script end
function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

$ru = getrusage();
echo "<br/><br/>This process used " . rutime($ru, $rustart, "utime") .
    " ms for its computations\n";
echo "It spent " . rutime($ru, $rustart, "stime") .
    " ms in system calls\n";