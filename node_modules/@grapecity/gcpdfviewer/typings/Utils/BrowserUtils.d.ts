export declare const isIETrident: boolean;
export declare function copyTextToClipboard(text: any): void;
export declare const isMobile: {
    Android: () => boolean;
    BlackBerry: () => boolean;
    iOS: () => boolean;
    iOSDesktopMode: () => boolean;
    iOSVer(): number[];
    likeMac: () => boolean;
    OperaMini: () => boolean;
    WindowsMobile: () => boolean;
    WindowsAny: () => boolean;
    anyMobile: () => boolean;
};
export declare type DeviceOutputScale = {
    sx: number;
    sy: number;
    scaled: boolean;
};
export declare function getDisplayPixelRatio(ctx?: any): DeviceOutputScale;
