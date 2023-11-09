export declare type EventHandler<TArgs> = (arg: TArgs) => any;
export declare type UnregisterHandler = () => any;
export declare class EventFan<TArgs> {
    private _eventHandlers;
    private _trigger;
    private static _lastKey;
    private static generateKey;
    private constructor();
    static createFor<TArgs>(): {
        event: EventFan<TArgs>;
        trigger: (e: TArgs) => void;
    };
    register(callback: (args: TArgs) => any): UnregisterHandler;
}
