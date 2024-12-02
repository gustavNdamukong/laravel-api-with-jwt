<?php

namespace App\Filters;

use Illuminate\Http\Request;


class ApiFilter {
    protected $allowedParams = [];

    protected $columnMap = [];

    protected $operatorMap = [];

    public function transform(Request $request) {
        $eloQuery = [];

        foreach ($this->allowedParams as $field_name => $operators) {
            $query = $request->query($field_name);

            if (!isset($query)) {
                continue;
            }
            $column = $this->columnMap[$field_name] ?? $field_name;
            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloQuery;
    }
}
