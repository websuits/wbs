import type { InsightsMethodMap, InsightsClient as _InsightsClient } from 'search-insights';
export type { Init as InsightsInit, AddAlgoliaAgent as InsightsAddAlgoliaAgent, SetUserToken as InsightsSetUserToken, GetUserToken as InsightsGetUserToken, OnUserTokenChange as InsightsOnUserTokenChange, } from 'search-insights';
export declare type InsightsClientMethod = keyof InsightsMethodMap;
export declare type InsightsClientPayload = {
    eventName: string;
    queryID: string;
    index: string;
    objectIDs: string[];
    positions?: number[];
};
declare type QueueItemMap = {
    [MethodName in keyof InsightsMethodMap]: [
        methodName: MethodName,
        ...args: InsightsMethodMap[MethodName]
    ];
};
declare type QueueItem = QueueItemMap[keyof QueueItemMap];
export declare type InsightsClient = _InsightsClient & {
    queue?: QueueItem[];
};
