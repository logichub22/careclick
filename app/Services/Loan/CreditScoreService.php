<?php

namespace App\Services\Loan;
use Illuminate\Support\Facades\DB;

class CreditScoreService
{
    public function calculateCreditScore($user)
    {
        $user_credit_score = $this->identityScore($user) + $this->loanCredibilityScore($user) + $this->transactionScore($user);

        return $user_credit_score;
    }

    /**
     * Get user score based on personal documents and
     * identification within Jamborow
     * @param object $user
     * @return mixed
     */
    private function identityScore($user)
    {
        $score = 0;
        // email verified
        $user->verified ? $score += 0.5 : $score += 0;

        // id document uploaded
        !is_null($user->identification_document) ? $score += 1 : $score += 0;

        // check avatar if is default
        $user->avatar === 'avatar.png' ? $score += 0.5 : $score += 0;

        return $score;
    }

    /**
     * Get user score based on past transactions in the app
     * @param object $user
     * @return mixed
     */
    private function transactionScore($user)
    {
        $score = 0;

        // check if user has transacted before
        $trans = DB::table('transactions')->where('user_id', $user->id)->get();
        count($trans) > 0 ? $score += 0.5 : $score += 0;

        $debits = DB::table('transactions')
            ->where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('txn_type', '=', 2);
            })->get();

        count($debits) > 0 ? $score += 1 : $score += 0;

        return $score;
    }

    /**
     * Get user score based on past loans
     * @param object $user
     * @return mixed
     */
    private function loanCredibilityScore($user)
    {
        $score = 0;

        $paid_loans = DB::table('loans')
                ->where('user_id', $user->id)
                ->where(function ($query) {
                    $query->where('status', '=', 3);
                })->get();

        count($paid_loans) > 0 ? $score += 1.5 : $score += 0;

        $defaulted_loans = DB::table('loans')
                ->where('user_id', $user->id)
                ->where(function ($query) {
                    $query->where('status', '=', 4);
                })->get();

        count($defaulted_loans) > 0 ? $score -= 1 : $score -= 0;

        return $score;
    }
}
