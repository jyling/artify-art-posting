<?php
class Validate
{
    private $_passed = false,
    $_err            = array(),
    $_db             = null;
    public function __construct()
    {
        $this->_db = DB::Run();
    }
    public static function sanitize($input)
    {
        return (trim(strip_tags(htmlspecialchars($input))));
    }
    public function check($source, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $condition => $con_value) {
                $value = trim($source[$item]);
                $item  = self::sanitize($item);
                if ($condition == 'required' && empty($value)) {
                    $this->addError("<strong>$rules[name]</strong> is needed", $item);
                } elseif (!empty($value)) {
                    switch ($condition) {
                        case 'min':
                            if (strlen($value) < $con_value) {
                                $this->addError("The <strong>$rules[name]</strong> need to be more than <strong>$con_value</strong> characters long", $item);
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $con_value) {
                                $this->addError("The <strong>$rules[name]</strong> need to be less than <strong>$con_value</strong> characters long", $item);
                            }
                            break;
                        case 'match':
                            if ($value != $source[$con_value]) {
                                $this->addError("The <strong>$rules[name]</strong> does not match <strong>$rules[match_name]</strong>", $item);
                            }
                            break;
                        case 'unique':
                            $check = DB::Run()->get($con_value, array($item, '=', $value));
                            if ($check->getCount()) {
                                $this->addError("<strong>$rules[name]</strong> already exist", $item);
                            }
                            break;
                        case 'charOnly':
                            if (!preg_match('/^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/', $value)) {
                                $this->addError("<strong>$rules[name]</strong> can only have english letters", $item);
                            }
                            break;
                        case 'charAndNums':
                            if ($con_value === true) {
                                if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $value)) {
                                    $this->addError("<strong>$rules[name]</strong> must contain at least one letters or numbers", $item);
                                }
                            }
                            break;
                        case 'startWithChar':
                            if ($con_value === true) {
                                if (is_numeric($value[0])) {
                                    $this->addError("<strong>$rules[name]</strong> must starts with a letters", $item);
                                }
                            }
                            break;
                        case 'regex':
                            if (!preg_match($rules['regex'], $value)) {
                                $this->addError("<strong>$rules[name]</strong> must be a valid format", $item);
                            }
                            break;
                        case 'existInDatabase':
                            $db = DB::run();
                            $db->get($con_value['table'], array($con_value['colName'], '=', $value));
                            if ($db->getCount() < 1) {
                                $this->addError("<strong>$rules[name]</strong> does not exist within the database", $item);
                            }
                            break;
                        case 'numsRange':
                            if ($rules['numsRange'][0] > $value && $rules['numsRange'][1] < $value) {
                                $this->addError("<strong>$rules[name]</strong> must be in range of " . $rules['numsRange'][0] . " to " . $rules['numsRange'][1], $item);
                            }
                            break;
                    }
                }

            }
        }
        if (empty($this->_err)) {
            $this->_passed = true;
        }
        return $this;
    }
    public function addError($input, $name)
    {
        $this->_err[$name] = $input;
    }
    public function getError()
    {return $this->_err;}
    public function passed()
    {return $this->_passed;}
}