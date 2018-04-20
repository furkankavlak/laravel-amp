<?php

namespace Just\Amp\Laravel;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\Factory as FactoryContract;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\ViewFinderInterface;

class AmpViewFactory extends Factory implements FactoryContract
{
    /**
     * @var string
     */
    protected $ampAffix;
    /**
     * @var string
     */
    protected $ampBoolName;

    /**
     * AmpViewFactory constructor.
     *
     * @param \Illuminate\View\Engines\EngineResolver $engines
     * @param \Illuminate\View\ViewFinderInterface    $finder
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     * @param string                                  $ampAffix
     * @param string                                  $ampBoolName
     */
    public function __construct(EngineResolver $engines, ViewFinderInterface $finder, Dispatcher $events, $ampAffix, $ampBoolName)
    {
        $this->ampAffix = $ampAffix;
        $this->ampBoolName = $ampBoolName;

        parent::__construct($engines, $finder, $events);
    }

    /**
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function make($view, $data = [], $mergeData = [])
    {
        $routeName = $this->getContainer()->make('router')->currentRouteName();

        if (preg_match('/\.amp$/', $routeName)) {
            if (isset($this->ampAffix))
                $view .= $this->ampAffix;

            if (isset($this->ampBoolName))
                $data[$this->ampBoolName] = true;
        }

        else if (isset($this->ampBoolName))
            $data[$this->ampBoolName] = false;

        return parent::make($view, $data, $mergeData);
    }
}
