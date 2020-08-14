<?php

namespace OZiTAG\Tager\Backend\Auth\Helpers;


class ProvidersHelper
{

    public static function getProviderAliases()
    {
        $providers = config('auth.providers') ?? [];
        return array_map(
            fn ($provider, $value) => $value['alias'] ?? self::getDefaultProviderAlias($provider),
            array_keys($providers), $providers
        );
    }

    public static function getProviderFromAlias($alias)
    {
        $providers = config('auth.providers') ?? [];
        $provider = array_filter($providers, fn ($i) => ($i['alias'] ?? '') === $alias);
        if($provider) {
            return array_key_first($provider);
        }
        return self::getDefaultProviderFromAlias($alias);
    }

    /**
     * @param $provider
     * @return mixed
     */
    protected static function getDefaultProviderAlias($provider) {
        return [
            'administrators' => 'admin',
            'users' => 'user',
        ][$provider];
    }

    /**
     * @param $provider
     * @return mixed
     */
    protected static function getDefaultProviderFromAlias($alias) {
        return [
            'admin' => 'administrators',
            'user' => 'users',
        ][$alias];
    }
}
