# Google Calendar Api
Home Test solution for Fullstack developer position
For backend part of test i used PHP to validate some data, and fire event create api to Google Calendar.


### Installing

1. mkdir on your localhost service, cd into and use commands bellow

2. Run commands in terminal

```
git clone https://github.com/milancomi/lltmstest4.git
composer update 
```

# PHP version problems; I tried 7.2 and 7.4 and both gave me same warning

Warning: count(): Parameter must be an array or an object that implements Countable in C:\laragon\www\lltmstest4\vendor\guzzlehttp\guzzle\src\Handler\CurlFactory.php on line 67


I found only one (not excelent) solution to solve that problem:

```
1.  \vendor\guzzlehttp\guzzle\src\Handler\CurlFactory.php on line 67
2. // if (count($this->handles) >= $this->maxHandles) { // OLD
3. Change with this line below
4. if (($this->handles ? count($this->handles) : 0) >= $this->maxHandles) {            // UPDATED !!!!!

```

# Conclusion
I wasn't sure whick one technology stack i should use, because it is not written in the task descriptiion.
Is the emphasis on frameworks or vanila/core codding.
