/**
 * Copyright (c) 2022 Strategio Digital s.r.o.
 * @author Jiří Zapletal (https://strategio.dev, jz@strategio.dev)
 */
import { IOrderBy } from '@/saas/api/types/IOrderBy'

export interface IShowParams {
    table: string,
    currentPage: number
    itemsPerPage: number
    schema?: boolean
    orderBy?: IOrderBy[]
}