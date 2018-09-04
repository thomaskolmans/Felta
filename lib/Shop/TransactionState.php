<?php
namespace lib\Shop;

abstract class TransactionState{

    const ACTIVE = 0;
    const PCOMMITED = 1;
    const FAILED = 2;
    const ABORTED = 3;
    const COMMITTED = 4;
    
}