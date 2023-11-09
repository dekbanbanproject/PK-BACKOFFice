/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { RulerLine, SignToolSettings } from '../ViewerOptions';
export declare type CanvasRulerProps = {
    settings: SignToolSettings;
    toolType: 'Draw' | 'Type' | 'Image';
};
export declare class CanvasRuler extends Component<CanvasRulerProps, any> {
    constructor(props: CanvasRulerProps, state: any);
    get settings(): SignToolSettings;
    get canvasSize(): {
        width: number;
        height: number;
    };
    getRulerLines(): RulerLine[] | null;
    ensureRulerColor(color?: string): string;
    ensureRulerSize(size?: number): number;
    ensureRulerPosition(position?: number): number;
    render(): JSX.Element | null;
}
