<?php

namespace App\Models;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of YearWiseScope
 *
 * @author Dalsukh Parmar <parmar.dalsukh@gmail.com>
 */
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Session;

class YearWiseScope implements Scope {

    /**
     * Apply scope on the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function apply(Builder $builder, Model $model) {
        $model = $model;
        if(Session::has('default_year')) {
            $default_year = Session::get('default_year');
            $builder->where($model->getTable().'.'.$model->getYearIdColumn(), $default_year->id);
        }
    }

    /**
     * Remove scope from the query.
     *
     * @param  Builder $builder
     * @return void
     */
    public function remove(Builder $builder) {
        $column = $builder->getModel()->getYearIdColumn();

        $query = $builder->getQuery();

        foreach ((array) $query->wheres as $key => $where) {
            // If the where clause is a soft delete date constraint, we will remove it from
            // the query and reset the keys on the wheres. This allows this developer to
            // include deleted model in a relationship result set that is lazy loaded.
            if ($this->isSoftDeleteConstraint($where, $column)) {
                unset($query->wheres[$key]);

                $query->wheres = array_values($query->wheres);
            }
        }
    }

}
