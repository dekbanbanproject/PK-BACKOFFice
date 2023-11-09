import { IStore, ChangeHandler } from '../api/PluginModel';
export declare class Store<TState, TMsg> implements IStore<TState, TMsg> {
    readonly update: (cmd: TMsg, state: TState) => TState;
    private _state;
    private readonly _handlers;
    private _lastKey;
    constructor(state: TState, update: (cmd: TMsg, state: TState) => TState, onChange?: ChangeHandler<TState>);
    dispatch: (msg: TMsg) => void;
    readonly state: TState;
    subscribe(onUpdate: ChangeHandler<TState>): () => void;
}
declare type CompositeMsg<T> = {
    type: keyof T;
    payload: any;
};
declare type InnerStoreMsg<TState, K extends keyof TState, TMsg extends CompositeMsg<TState>> = TMsg extends {
    type: K;
    payload: infer R;
} ? R : never;
export declare class CompositeStore<TState, TMsg extends CompositeMsg<TState>> implements IStore<TState, TMsg> {
    private readonly _index;
    private _dispose;
    private _onUpdateEvent;
    private _triggerUpdate;
    constructor(_index: {
        [P in keyof TState]: IStore<TState[P], InnerStoreMsg<TState, P, TMsg>>;
    });
    replace(updated: Partial<{
        [P in keyof TState]: IStore<TState[P], InnerStoreMsg<TState, P, TMsg>>;
    }>): void;
    readonly state: TState;
    dispatch(msg: TMsg): void;
    subscribe: (onUpdate: ChangeHandler<TState>) => () => void;
}
export {};
