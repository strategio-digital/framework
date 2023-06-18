/**
 * Copyright (c) 2022 Strategio Digital s.r.o.
 * @author Jiří Zapletal (https://strategio.digital, jz@strategio.digital)
 */

import api from '@/saas/api'
import { IResponse } from '@/saas/api/types/IResponse'
import { IShowParams } from '@/saas/api/types/IShowParams'
import { IRow } from '@/saas/api/types/IRow'
import { ISchemaRow } from '@/saas/api/types/ISchemaRow'
import { IPagination } from '@/saas/api/types/IPagination'

export interface IResp extends IResponse {
    data: {
        pagination: IPagination
        items: IRow[]
        schema?: ISchemaRow[]
    }
}

const show = async (params: IShowParams): Promise<IResp> => {
    const resp = await api.fetch(`/saas/crud/show`, {
        method: 'POST',
        body: JSON.stringify(params)
    })

    return { ...resp, data: resp.data }
}

export default show