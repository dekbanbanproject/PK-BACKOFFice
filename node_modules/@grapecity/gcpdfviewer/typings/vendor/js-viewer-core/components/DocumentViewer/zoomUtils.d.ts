import { ZoomSettings } from './types';
export declare function getZoomFactor(zoom: ZoomSettings, pageSize: {
    width: string;
    height: string;
} | null, viewportSize: {
    width: number;
    height: number;
}): number;
