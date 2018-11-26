<?php
/**
 * Created by PhpStorm.
 * User: harmakit
 * Date: 18/11/2018
 * Time: 11:44
 */

include_once 'Characteristic.php';
include_once 'Observation.php';
include_once 'CharacteristicFabric.php';
include_once 'ObservationFabric.php';

function printMenu($T) {
    print(
        'Menu:'  . PHP_EOL . PHP_EOL .
        't - Set machine type (T)'  . PHP_EOL .
        'fc - Set features count'  . PHP_EOL .
        'p - Set penalty value (p)'  . PHP_EOL .
        's - Set stopping point value (s)'  . PHP_EOL .
        'pos - Set positive observations ('  . ($T === 'conjunction' ? 'N' : 'P') . ')' . PHP_EOL .
        'neg - Set negative observations ('  . ($T === 'conjunction' ? 'P' : 'N') . ')' . PHP_EOL .
        'h - Set characteristics (H)'  . PHP_EOL .
        'run - Run algorithm'  . PHP_EOL .
        'q - Exit' . PHP_EOL
    );
    for ($i = 0; $i < 20; $i++) {
        print('_');
    }
    print(PHP_EOL);
}

function printParameters(
    $T,
    $featuresCount,
    $positive,
    $negative,
    $p,
    $s,
    $characteristics
) {
    if (empty($positive)) {
        $positiveObservations = '[ ]';
    } else {
        $positiveObservations = '[' . PHP_EOL;
        foreach ($positive as $observation) {
            $positiveObservations .= "\t" . implode(',', $observation->getObservationValues()) . PHP_EOL;
        }
        $positiveObservations .= ']';
    }

    if (empty($negative)) {
        $negativeObservations = '[ ]';
    } else {
        $negativeObservations = '[' . PHP_EOL;
        foreach ($negative as $observation) {
            $negativeObservations .= "\t" . implode(',', $observation->getObservationValues()) . PHP_EOL;
        }
        $negativeObservations .= ']';
    }

    if (empty($characteristics)) {
        $H = '[ ]';
    } else {
        $H = '[' . PHP_EOL;
        foreach ($characteristics as $characteristic) {
            $characteristicValues = $characteristic->getCharacteristicsValues();
            foreach ($characteristicValues as $key => $characteristicValue) {
                if (null === $characteristicValue) {
                    $characteristicValues[$key] = '_';
                }
            }
            $H .= "\t" . implode(',', $characteristicValues) . PHP_EOL;
        }
        $H .= ']';
    }


    print(
        'T -> '  . $T . PHP_EOL .
        'features count -> '  . ($featuresCount ?? 'null') . PHP_EOL .
        'p -> '  . $p . PHP_EOL .
        's -> ' . ($s ?? 'null')  . PHP_EOL .
        'positive observations  -> ' . $positiveObservations  . PHP_EOL .
        'negative observations  -> ' . $negativeObservations  . PHP_EOL .
        'characteristics  -> ' . $H  . PHP_EOL
    );
}

system('clear');
print('BuildSCM'  . PHP_EOL . PHP_EOL);
$T = 'disjunction';
$featuresCount = null;
$p = 1;
$s = null;
$positive = [];
$negative = [];
$characteristics = [];

$observationFabric = new ObservationFabric();
$characteristicsFabric = new CharacteristicFabric();

while (true) {

    printMenu($T);
    printParameters($T, $featuresCount, $positive, $negative, $p, $s, $characteristics);
    $input = readline(PHP_EOL . 'Choose menu item: ');

    switch ($input) {
        case 't':
            $T = null;
            while (!in_array($T, ['conjunction', 'disjunction'])) {
                $T = readline(PHP_EOL . 'Enter value (conjunction or disjunction): ');
            }
            system('clear');
            break;
        case 'fc':
            $featuresCount = (int) readline(PHP_EOL . 'Enter value: ');
            $observationFabric->setParametersCount($featuresCount);
            $characteristicsFabric->setParametersCount($featuresCount);
            system('clear');
            break;
        case 'p':
            $p = (double) readline(PHP_EOL . 'Enter value: ');
            system('clear');
            break;
        case 's':
            $s = readline(PHP_EOL . 'Enter value: ');
            if ($s === 'null') {
                $s = null;
            } else {
                $s = (int) $s;
            }
            system('clear');
            break;
        case 'pos':
            system('clear');
            if (null === $featuresCount || 0 === $featuresCount) {
                $featuresCount = (int) readline('Please set features count: ');
                print(PHP_EOL);
                if ($featuresCount === 0) {
                    break;
                }
                $observationFabric->setParametersCount($featuresCount);
                $characteristicsFabric->setParametersCount($featuresCount);
            }
            $observationFabric->setType('P');
            $positive = [];
            print(
                'Enter ' . $featuresCount . ' binary features separated with commas' .
                PHP_EOL . 'Example (for 4 features): 1,0,1,1' .
                PHP_EOL . 'Leave a blank line when you\'re done' .
                PHP_EOL . PHP_EOL
            );
            while (true) {
                $string = readline();
                if ($string === '') {
                    system('clear');
                    break;
                }
                $array = explode(',', $string);
                if (count($array) === $featuresCount) {
                    $positive[] = $observationFabric->create($array);
                } else {
                    print(PHP_EOL . 'Wrong features count! Please enter ' . $featuresCount . ' features' . PHP_EOL);
                }
            }
            break;
        case 'neg':
            system('clear');
            if (null === $featuresCount || 0 === $featuresCount) {
                $featuresCount = (int) readline('Please set features count: ');
                print(PHP_EOL);
                if ($featuresCount === 0) {
                    break;
                }
                $observationFabric->setParametersCount($featuresCount);
                $characteristicsFabric->setParametersCount($featuresCount);
            }
            $observationFabric->setType('N');
            $negative = [];
            print(
                'Enter ' . $featuresCount . ' binary features separated with commas' .
                PHP_EOL . 'Example (for 4 features): 0,1,0,0' .
                PHP_EOL . 'Leave a blank line when you\'re done' .
                PHP_EOL . PHP_EOL
            );
            while (true) {
                $string = readline();
                if ($string === '') {
                    system('clear');
                    break;
                }
                $array = explode(',', $string);
                if (count($array) === $featuresCount) {
                    $negative[] = $observationFabric->create($array);
                } else {
                    print(PHP_EOL . 'Wrong features count! Please enter ' . $featuresCount . ' features' . PHP_EOL);
                }
            }
            break;
        case 'h':
            system('clear');
            if (null === $featuresCount || 0 === $featuresCount) {
                $featuresCount = (int) readline('Please set features count: ');
                print(PHP_EOL);
                if ($featuresCount === 0) {
                    break;
                }
                $observationFabric->setParametersCount($featuresCount);
                $characteristicsFabric->setParametersCount($featuresCount);
            }
            $characteristics = [];
            print(
                'Enter ' . $featuresCount . ' binary features separated with commas' .
                PHP_EOL . 'For creating ANY values in characteristics enter \'x\'' .
                PHP_EOL . 'Example (for 4 features): 1,x,x,1' .
                PHP_EOL . 'Leave a blank line when you\'re done' .
                PHP_EOL . PHP_EOL
            );
            while (true) {
                $string = readline();
                if ($string === '') {
                    system('clear');
                    break;
                }
                $array = explode(',', $string);
                if (count($array) === $featuresCount) {
                    $characteristics[] = $characteristicsFabric->create($array);
                } else {
                    print(PHP_EOL . 'Wrong features count! Please enter ' . $featuresCount . ' features' . PHP_EOL);
                }
            }
            break;
        case 'run':
            system('clear');
            if ($featuresCount > 0 && isset($p, $positive, $negative, $characteristics)) {
                if (!featuresViolation($positive, $featuresCount)) {
                    if (!featuresViolation($negative, $featuresCount)) {
                        if (!featuresViolation($characteristics, $featuresCount)) {
                            runSCM($T, $positive, $negative, $p, $s, $characteristics);
                        } else {
                            print(
                                'Wrong features in characteristics count. It must be ' . $featuresCount . PHP_EOL . PHP_EOL
                            );
                        }
                    } else {
                        print(
                            'Wrong features in negative observations count. It must be ' . $featuresCount . PHP_EOL . PHP_EOL
                        );
                    }
                } else {
                    print(
                        'Wrong features in positive observations count. It must be ' . $featuresCount . PHP_EOL . PHP_EOL
                    );
                }
            } else {
                print(
                    'Please check your input data. Something is not set.' .
                    PHP_EOL . 'Data: P, N, H, p' . PHP_EOL . PHP_EOL
                );
            }

            break;
        case 'q':
            exit(0);
            break;
        default:
            system('clear');
            break;

    }
}

function runSCM(
    $T,
    $positive,
    $negative,
    $p,
    $s,
    $characteristics
)
{
    print (PHP_EOL . PHP_EOL);
    if ($T === 'conjunction') {
        $P = $negative;
        $N = $positive;
    } else {
        $P = $positive;
        $N = $negative;
    }

    $H = $characteristics;
    $Hx = [];

    $Q = [];
    $R = [];

    foreach ($H as $i => $hi) {
        list($Q_covers, $Q_errors) = $hi->getCharacteristicsOverlay($N);
        $Q[$i] = $Q_covers;
        list($R_covers, $R_errors) = $hi->getCharacteristicsOverlay($P);
        $R[$i] = $R_errors;
    }
    unset($Q_covers, $Q_errors, $R_covers, $R_errors);

    while (true) {

        $U = [];
        if (count($H) === 0) {
            print('All characteristics were exhausted' . PHP_EOL);
            print('Here they are:' . PHP_EOL);
            foreach ($Hx as $i => $char) {
                print('char[' . $i . ']  ->  {' . implode(', ', $char->getCharacteristicsValues()) . ' }' . PHP_EOL);
            }
            return (0);
        }
        foreach ($H as $i => $hi) {
            $U[$i] = count($Q[$i]) - $p * count($R[$i]);
        }

        $kKey = array_keys($U, max($U))[0];
        $hk = $H[$kKey];

        $Hx[] = $hk;

        foreach ($N as $obs) {
            foreach ($Q[$kKey] as $Qk_index => $Qk) {
                if ($obs === $Qk) {
                    array_splice($N, array_keys($N, $obs)[0], 1);
                }
            }
        }
        foreach ($P as $obs) {
            foreach ($R[$kKey] as $Rk_index => $Rk) {
                if ($obs === $Rk) {
                    array_splice($P, array_keys($P, $obs)[0], 1);
                }
            }
        }

        foreach ($Q as $i => $Qi) {
            foreach ($Qi as $j => $Qi_obs) {
                foreach ($Q[$kKey] as $Qk_index => $Qk) {
                    if ($obs === $Qk) {
                        array_splice($Q[$i], $j, 1);
                    }
                }
            }
        }

        foreach ($R as $i => $Ri) {
            foreach ($Ri as $j => $Ri_obs) {
                foreach ($R[$kKey] as $Rk_index => $Rk) {
                    if ($obs === $Rk) {
                        array_splice($R[$i], $j, 1);
                    }
                }
            }
        }


        array_splice($H, $kKey, 1);

        if (count($N) === 0 || count($Hx) === $s) {
            print('Best characteristics found!' . PHP_EOL);
            if (count($N) !== 0) {
                print('(Early stopping)' . PHP_EOL);
            }
            print('Here they are:' . PHP_EOL );
            foreach ($Hx as $i => $char) {
                print('characteristic[' . $i . ']  ->  {' . implode(' , ', $char->getCharacteristicsValues()) . ' }' . PHP_EOL);
            }
            print (PHP_EOL);
            return 0;
        }
    }
}

function featuresViolation($array, $featuresCount) {
    foreach ($array as $item) {
        if ($item instanceof Observation) {
            $values = $item->getObservationValues();
        } elseif ($item instanceof Characteristic) {
            $values = $item->getCharacteristicsValues();
        }
        if (count($values) !== $featuresCount) {
            return true;
        }
    }
    return false;
}





