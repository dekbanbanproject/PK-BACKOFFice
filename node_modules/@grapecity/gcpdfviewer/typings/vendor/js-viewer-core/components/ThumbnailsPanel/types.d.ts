import { PageInfo } from '../DocumentViewer/types';
export declare type ThumbnailLocation = {
    top: number;
    bottom: number;
};
export declare type Size = {
    width: string;
    height: string;
};
export declare type Thumbnail = {
    content: string;
    size: Size;
    originalSize: Size;
    scaleFactor: number;
};
export declare type Model = {
    pageIndex: number;
    pageCount: number;
    thumbnails: Thumbnail[];
    viewportHeight: number;
    scrollTopPosition: number;
};
export declare type Command = {
    type: 'ProcessViewerState';
    payload: {
        pageBuffer: PageInfo[];
        pageCount: number;
        pageIndex: number;
    };
} | {
    type: 'ProcessScrollInfo';
    payload: {
        scrollTopPosition?: number;
        viewportHeight?: number;
    };
};
