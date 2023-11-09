/// <reference path="../../../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { PageViewProps } from './types';
declare type SinglePageViewState = {
    zoomFactor: number;
    viewportWidth: number;
    viewportHeight: number;
};
export declare class SinglePageView extends Component<PageViewProps, SinglePageViewState> {
    private _view;
    private _lastScrollRequestNo;
    private _moveHandler;
    state: {
        zoomFactor: number;
        viewportHeight: number;
        viewportWidth: number;
    };
    private onClick;
    private checkMouseMode;
    componentDidMount(): void;
    componentWillUnmount(): void;
    componentDidUpdate(): void;
    render(): JSX.Element;
    private setViewportSize;
    private getPageSize;
    private getPageViewportSize;
}
export {};
