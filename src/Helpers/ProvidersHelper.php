<?php

namespace OZiTAG\Tager\Backend\Auth\Helpers;


use Illuminate\Support\Facades\Config;

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
        if ($provider) {
            return array_key_first($provider);
        }
        return self::getDefaultProviderFromAlias($alias);
    }

    /**
     * @param $provider
     * @return mixed
     */
    protected static function getDefaultProviderAlias($provider) {
        $alias = Config::get('tager-auth.providers_aliases')[$provider] ?? null;

        if (!$alias) {
            throw new \RuntimeException('Tager Auth Config - provider alias not found');
        }

        return $alias;
    }

    /**
     * @param $provider
     * @return mixed
     */
    protected static function getDefaultProviderFromAlias($alias) {
        $provider = array_flip(Config::get('tager-auth.providers_aliases'))[$alias] ?? null;

        if (!$provider) {
            throw new \RuntimeException('Tager Auth Config - provider not found');
        }

        return $provider;
    }
}
